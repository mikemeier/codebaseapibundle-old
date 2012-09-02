<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Credential;

use Ibrows\Bundle\CodebaseApiBundle\Command\AbstractCommand;

use Ibrows\Bundle\CodebaseApiBundle\Auth\AuthFactory;
use Ibrows\Bundle\CodebaseApiBundle\Credentials\Credentials;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SaveCredentialsCommand extends AbstractCommand
{
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('codebase:credentials:save')
            ->setDescription('Saves Codebase API Credentials')
        
            ->addArgument('username', InputArgument::REQUIRED, 'Codebase API Username')
            ->addArgument('key', InputArgument::REQUIRED, 'Codebase API Key')
            ->addArgument('passphrase', InputArgument::REQUIRED, 'Passphrase for Password Encryption')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        
        $credentials = new Credentials(
            $input->getArgument('username'), 
            $input->getArgument('key')
        );
        
        $output->writeln('Saving Credentials');

        $data = serialize($credentials);
        
        $credentialsStore = $this->getCredentialsStore();
        $credentialsStore->set($input->getArgument('passphrase'), $this->getCredentialsKey(), $data);
        $credentialsStore->flush();
        
        $output->writeln('<info>done</info>');
    }
}