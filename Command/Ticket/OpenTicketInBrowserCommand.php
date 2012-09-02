<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Ticket;

use Ibrows\Bundle\CodebaseApiBundle\Command\AbstractAuthCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OpenTicketInBrowserCommand extends AbstractAuthCommand
{
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('CodebaseApi:OpenTicketInBrowser')
            ->setDescription('Open Ticket in Browser')
            ->addArgument('ticketnr', InputArgument::REQUIRED, 'The Ticket-Number')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        
        if(strtolower(substr(PHP_OS, 0, 3)) == "win"){
            return $output->writeln('<error>Windows not supported yet</error>');
        }
        
        $ticketUri = $this->getContainer()->getParameter('ibrows.codebase.uri') . $this->getProjectName($input).'/tickets/'. 
            (int)$input->getArgument('ticketnr');

        $output->writeln(shell_exec('open '. escapeshellarg($ticketUri)));
    }
    
}