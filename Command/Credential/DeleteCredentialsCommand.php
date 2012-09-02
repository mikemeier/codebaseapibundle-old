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

class DeleteCredentialsCommand extends AbstractCommand
{
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('CodebaseApi:DeleteCredentials')
            ->setDescription('Deletes Codebase API Credentials')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        parent::execute($input, $output);
        
        $output->writeln('Deleting Credentials');

        $credentialsStore = $this->getCredentialsStore();
        $credentialsStore->remove($this->getCredentialsKey());
        
        $output->writeln('<info>done</info>');
    }
}