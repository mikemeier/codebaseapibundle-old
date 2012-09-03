<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command;

use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\LoopAndReadHelper;
use Ibrows\Bundle\CodebaseApiBundle\StoreAndEncryption\StoreAndEncryption;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class AbstractCommand extends ContainerAwareCommand
{

    /**
     * @var InputInterface 
     */
    protected $input;
    
    /**
     * @var OutputInterface 
     */
    protected $output;
    
    protected function configure()
    {
        $this
            ->addOption('recordname', 'r', InputOption::VALUE_REQUIRED, 'Records the current input')
            ->addOption('passphrase', 'p', InputOption::VALUE_REQUIRED, 'Passphrase for Encryption')
        ;
    }
    
    /**
     * @param InputInterface $input
     * @param OutputInterface $output 
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        
        $this->setOutputStyles();
        
        $this->saveInputIfOptionIsSet();
    }
    
    /**
     * @return string 
     */
    protected function getPassphrase()
    {
        return $this->getInput()->getOption('passphrase');
    }
    
    protected function saveInputIfOptionIsSet()
    {
        $input = $this->getInput();
        $saveInputname = $input->getOption('recordname');
        
        if(!$saveInputname){
            return;
        }
        
        $passphrase = $this->getPassphrase();
            
        if(!$passphrase){
            throw new \InvalidArgumentException("Need passphrase option for encryption");
        }

        $input->setOption('recordname', false);

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
        $shortcutStore->set($passphrase, $saveInputname, $data);
        $shortcutStore->flush();

        $this->getOutput()->writeln('<info>Shortcut "'. $saveInputname .'" saved</info>');
    }
    
    /**
     * @return LoopAndReadHelper 
     */
    protected function getNewLoopAndReaderHelper()
    {
        $helper = new LoopAndReadHelper(
            $this->getOutput(),
            $this->getContainer()->getParameter('ibrows_codebase_api.loopinterval')
        );
        $helper->setTriggers($this->getTriggers());
        
        return $helper;
    }
    
    /**
     * @return InputInterface 
     */
    protected function getInput()
    {
        return $this->input;
    }
    
    /**
     * @return OutputInterface 
     */
    protected function getOutput()
    {
        return $this->output;
    }
    
    protected function setOutputStyles()
    {
        $style = new OutputFormatterStyle('cyan');
        $this->getOutput()->getFormatter()->setStyle('data', $style);
    }
    
    /**
     * @return string 
     */
    protected function getCredentialsKey()
    {
        return 'credentials';
    }
    
    /**
     * @return StoreAndEncryption 
     */
    protected function getCredentialsStore()
    {
        return $this->getContainer()->get('ibrows.codebaseapi.storeandecryption.credential');
    }
    
    /**
     * @return StoreAndEncryption 
     */
    protected function getShortcutStore()
    {
        return $this->getContainer()->get('ibrows.codebaseapi.storeandecryption.shortcut');
    }
    
    /**
     * @return TriggerInterface[] $triggers 
     */
    protected function getTriggers()
    {
        return array();
    }
    
}