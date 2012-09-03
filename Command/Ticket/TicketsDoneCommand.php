<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Ticket;

use Ibrows\Bundle\CodebaseApiBundle\Auth\AuthFactory;
use Ibrows\Bundle\CodebaseApiBundle\Credentials\Credentials;
use Ibrows\Bundle\CodebaseApiBundle\Query\Ticket\TicketOptions;
use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\TicketObject;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class TicketsDoneCommand extends AbstractTicketCommand
{
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('codebase:tickets:done')
            ->setDescription('Shows Tickets Done')
            ->addOption('updatedate', 'd', InputOption::VALUE_OPTIONAL, 'Date to search for - timetostring parameters allowed', 'now')
            ->addOption('watch', 'w', InputOption::VALUE_NONE, 'Checks every 10 seconds for new Tickets')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        
        $watch = $this->getInput()->getOption('watch');

        if(!$watch){
            $this->outputOptionsAndTickets();
            return;
        }
        
        $this->getNewLoopAndReaderHelper()
            ->setLoopClosure($this->getLoopClosure())
            ->run()
        ;
    }
    
    /**
     * @return TicketOptions 
     */
    protected function getDefaultOptions()
    {
        $updateDate = new \DateTime();
        $updateDate->setTimestamp(strtotime($this->getInput()->getOption('updatedate')));
        
        return parent::getDefaultOptions()
            ->setAssignee(TicketOptions::ASSIGNEE_ME)
            ->setResolution(TicketOptions::RESOLUTION_CLOSED)
            ->setUpdatedDate($updateDate)
            
            ->setSort(TicketOptions::SORT_UPDATED_AT)
            ->setOrder(TicketOptions::ORDER_DESC)
        ;
    }
    
}