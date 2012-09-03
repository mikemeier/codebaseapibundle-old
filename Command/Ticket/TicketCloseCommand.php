<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Ticket;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TicketCloseCommand extends AbstractTicketCommand
{
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('codebase:ticket:close')
            ->setDescription('Close Ticket')
                
            ->addArgument('ticketnr', InputArgument::REQUIRED, 'The Ticket-Number')
            ->addArgument('message', InputArgument::OPTIONAL, 'Message to close the ticket')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        
        $this->closeTicket($input->getArgument('ticketnr'), $input->getArgument('message'));
    }
    
}