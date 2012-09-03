<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Ticket;

use Ibrows\Bundle\CodebaseApiBundle\Command\AbstractCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OpenTicketInBrowserCommand extends AbstractCommand
{
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('codebase:ticket:open-in-browser')
            ->setDescription('Open Ticket in Browser')
            ->addArgument('projectname', InputArgument::REQUIRED, 'Codebase Project Name')
            ->addArgument('ticketnr', InputArgument::REQUIRED, 'The Ticket-Number')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        
        if(strtolower(substr(PHP_OS, 0, 3)) == "win"){
            return $output->writeln('<error>Windows not supported yet</error>');
        }
        
        $ticketUri = $this->getContainer()->getParameter('ibrows_codebase_api.uri.company') . 
            $input->getArgument('projectname').'/tickets/'. (int)$input->getArgument('ticketnr');

        $output->writeln(shell_exec('open '. escapeshellarg($ticketUri)));
    }
    
}