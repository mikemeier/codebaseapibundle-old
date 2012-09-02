<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Ticket;

use Ibrows\Bundle\CodebaseApiBundle\Auth\AuthFactory;
use Ibrows\Bundle\CodebaseApiBundle\Credentials\Credentials;
use Ibrows\Bundle\CodebaseApiBundle\Query\Ticket\TicketOptions;
use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\TicketObject;

use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\LoopAndReadHelper;
use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\ReadTrigger;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class TicketsOpenCommand extends AbstractTicketCommand
{
    
    protected $options = false;
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('CodebaseApi:TicketsOpen')
            ->setDescription('Shows Tickets Open')
            ->addOption('watch', 'w', InputOption::VALUE_NONE, 'Checks every 10 seconds for new Tickets')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        parent::execute($input, $output);
        
        $watch = $this->getInput()->getOption('watch');
        
        if(!$watch){            
            $this->outputOptions();
            
            $tickets = $this->getTickets();
            $this->outputTickets($tickets);
            
            if(count($tickets) == 0){
                $output->writeln('<info>No Tickets found</info>');
            }
            
            return;
        }
        
        $this->getNewLoopAndReaderHelper()
            ->setLoopInterval(10)
            ->setLoopClosure($this->getLoopClosure())
            ->run()
        ;
    }
    
    /**
     * @return TicketOptions 
     */
    protected function getDefaultOptions()
    {           
        return parent::getDefaultOptions()
            ->setAssignee(TicketOptions::ASSIGNEE_ME)
            ->setResolution(TicketOptions::RESOLUTION_OPEN)
            
            ->setSort(TicketOptions::SORT_PRIORITY)
            ->setOrder(TicketOptions::ORDER_DESC)
        ;
    }
    
    protected function outputTickets(array $tickets)
    {
        /* @var $ticket TicketObject */
        foreach($tickets as $ticket){
            $prio = $ticket->getPriority()->getName();
            $this->getOutput()->writeln(
                '<prio-'. $prio .'>' .
                    'Ticket #' . $ticket->getId() ."\t".
                    'Prio: '. $prio ."\t".
                    'Status: '. $ticket->getStatus()->getName() ."\t".
                    $ticket->getSummary() .
                '</prio-'. $prio .'>'
            );
        }
    }
    
}