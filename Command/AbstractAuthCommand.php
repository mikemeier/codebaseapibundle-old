<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command;

use Ibrows\Bundle\CodebaseApiBundle\Credentials\Credentials;
use Ibrows\Bundle\CodebaseApiBundle\StoreAndEncryption;

use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\Trigger\TriggerInterface;
use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\Trigger\ClosureTrigger;

use Ibrows\Bundle\CodebaseApiBundle\Transport\TransportFactory;
use Ibrows\Bundle\CodebaseApiBundle\Transport\TransportInterface;
use Ibrows\Bundle\CodebaseApiBundle\Transport\Exception\AccessDeniedException;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Command\Command;

abstract class AbstractAuthCommand extends AbstractCommand
{
    
    /**
     * @var Credentials 
     */
    protected $credentials;
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->addArgument('projectname', InputArgument::REQUIRED, 'Codebase Project Name')
                
            ->addOption('username', 'u', InputOption::VALUE_OPTIONAL, 'Codebase API Username')
            ->addOption('key', 'k', InputOption::VALUE_OPTIONAL, 'Codebase API Key')
            ->addOption('passphrase', 'p', InputOption::VALUE_OPTIONAL, 'Passphrase for Credentials Decryption')
            ->addOption('transport', 't', InputOption::VALUE_OPTIONAL, 'Transport for Communication', 'curl')
            ->addOption('saveinputname', 's', InputOption::VALUE_REQUIRED, 'Saves the current input')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        
        $credentials = $this->credentials = $this->getCredentialsFromInput();
        
        if(!$credentials){
            throw new AccessDeniedException("No Credentials found");
        }
        
        $this->transport = $this->getTransportFactory()->getInstance(
            $input->getOption('transport'), 
            $credentials
        );
        
        $this->saveInputIfOptionIsSet(md5($credentials->getKey()));
    }
    
    protected function saveInputIfOptionIsSet($pass)
    {
        $input = $this->getInput();
        $saveInputname = $input->getOption('saveinputname');
        
        if($saveInputname){
            
            $input->setOption('saveinputname', false);
            
            $arguments = array();
            foreach($input->getArguments() as $key => $value){
                $arguments[$key] = $value;
            }

            $saveInput = new ArrayInput($arguments, $this->getDefinition());
            
            foreach($input->getOptions() as $key => $value){
                $saveInput->setOption($key, $value);
            }
            
            $data = serialize($saveInput);
            
            $shortcutStore = $this->getShortcutStore();
            $shortcutStore->set($pass, $saveInputname, $data);
            $shortcutStore->flush();
            
            $this->getOutput()->writeln('<info>Shortcut "'. $saveInputname .'" saved</info>');
        }
    }
    
    /**
     * @return TransportFactory 
     */
    protected function getTransportFactory()
    {
        return $this->getContainer()->get('ibrows.codebase.transport.factory');
    }
    
    /**
     * @return string 
     */
    protected function getProjectName()
    {
        return $this->getInput()->getArgument('projectname');
    }
    
    /**
     * @return array
     */
    protected function getDefaultNewCommandInputArgs(Command $command, array $merge = array())
    {
        $credentials = $this->getCredentials();
        
        return array_merge(array(
            'command' => $command->getName(),
            'projectname' => $this->getProjectName(),
            '--username' => $credentials->getUsername(),
            '--key' => $credentials->getKey()
        ), $merge);
    }
    
    /**
     * @return TriggerInterface[] $triggers 
     */
    protected function getTriggers()
    {
        $self = $this;
        
        return array_merge(parent::getTriggers(), array(
            new ClosureTrigger('|^stop$|', function(){
                die();
            }),
                    
            new ClosureTrigger('|^open (\d+)$|', function() use ($self){
                $command = $self->getApplication()->find('codebase:ticket:open-in-browser');
                
                $arguments = $self->getDefaultNewCommandInputArgs($command, array(
                    'ticketnr' => func_get_arg(0)
                ));

                $command->run(new ArrayInput($arguments), $self->getOutput());
            })
        ));
    }
    
    /**
     * @return Credentials 
     */
    protected function getCredentials()
    {
        return $this->credentials;
    }
    
    /**
     * @return TransportInterface 
     */
    protected function getTransport()
    {
        return $this->transport;
    }
    
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|Credentials 
     */
    protected function getCredentialsFromInput()
    {
        $input = $this->getInput();
        $output = $this->getOutput();
        
        if($input->getOption('passphrase')){
            return $this->getCredentialsFromStore($input->getOption('passphrase'), $output);
        }
        
        $username = $input->getOption('username');
        $key = $input->getOption('key');
        
        if(!$username OR !$key){
            $output->writeln('<error>Need options --username and --key or --passphrase</error>');
            return null;
        }
        
        return new Credentials($username, $key);
    }
    
    /**
     * @param string $passphrase 
     */
    protected function getCredentialsFromStore($passphrase)
    {
        $output = $this->getOutput();
        
        $credentialStore = $this->getCredentialsStore();
        $credentialsData = $credentialStore->get($passphrase, $this->getCredentialsKey(), false);
        
        if(!$credentialsData){
            $output->writeln('<error>No Credentials found</error>');
            return;
        }
        
        $credentials = @unserialize($credentialsData);
        
        if(false === $credentials){
            $output->writeln('<error>Passphrase wrong, couldnt decrypt credentials</error>');
            return;
        }
        
        return $credentials;
    }
    
}