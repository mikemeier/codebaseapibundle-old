<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Ticket;

use Ibrows\Bundle\CodebaseApiBundle\Command\AbstractAuthCommand;
use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\Trigger\ClosureTrigger;

use Ibrows\Bundle\CodebaseApiBundle\Query\Ticket\TicketOptions;
use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\TicketObject;

use Ibrows\Bundle\CodebaseApiBundle\Transport\Exception\UnprocessableEntityException;
use Ibrows\Bundle\CodebaseApiBundle\Transport\Exception\InvalidStatusCodeException;
use Ibrows\Bundle\CodebaseApiBundle\Transport\Exception\NotFoundException;
use Ibrows\Bundle\CodebaseApiBundle\Transport\Exception\AccessDeniedException;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

abstract class AbstractTicketCommand extends AbstractAuthCommand
{
    
    protected $options = false;
    
    /**
     * @return TicketOptions 
     */
    protected function getDefaultOptions()
    {
        return new TicketOptions();
    }

    protected function outputOptions()
    {
        $this->getOutput()->writeln('<comment>Options: '. $this->getOptions()->getQuery(', ') .'</comment>');
    }
    
    protected function getLoopClosure()
    {
        $self = $this;
        
        $compareFunc = function(TicketObject $a, TicketObject $b)
        {
            if($a->getUniqueId() === $b->getUniqueId()) {
                return 0;
            }
            return ($a>$b)?1:-1;
        };
        
        return function() use ($self, $compareFunc){
            
            static $oldTickets = array();
            static $informedAboutNoTickets = false;
            
            $tickets = $self->getTickets();
            
            $newTickets = array_udiff($tickets, $oldTickets, $compareFunc);
            $deletedTickets = array_udiff($oldTickets, $tickets, $compareFunc);

            $date = date('d.m.Y H:i:s');
            
            if(count($deletedTickets) > 0){
                $informedAboutNoTickets = false;
                $self->outputOptions();
                $self->getOutput()->writeln('------- New View '. $date .' -------');
                $self->outputTickets($tickets);
            }elseif(count($newTickets) > 0){
                $informedAboutNoTickets = false;
                $self->getOutput()->writeln('------- Addition '. $date .' -------');
                $self->outputTickets($newTickets);
            }elseif(count($tickets) == 0 && false == $informedAboutNoTickets){
                $informedAboutNoTickets = true;
                $self->getOutput()->writeln('<info>No Tickets found</info>');
            }

            $oldTickets = $tickets;
        };
    }
    
    /**
     * @return TriggerInterface[] $triggers 
     */
    protected function getTriggers()
    {
        $self = $this;
        
        return array_merge(parent::getTriggers(), array(
            new ClosureTrigger('|^refresh$|', function() use ($self){
                $self->outputOptionsAndTickets();
            }),
                    
            new ClosureTrigger('|^sort (\w+) ?(\w+)?$|', function() use ($self){
                $options = $self->getOptions();
                $options->setSort(func_get_arg(0));
                
                $order = @func_get_arg(1);
                if($order){
                    $options->setOrder($order);
                }
                
                $self->outputOptionsAndTickets();
            }),
                    
            new ClosureTrigger('|^assignee ?(\w+)?$|', function() use ($self){
                $options = $self->getOptions();
                
                $assignee = @func_get_arg(0);
                if(!$assignee){
                    $options->removeAssignee();
                }else{
                    $options->setAssignee($assignee);
                }
                
                $self->outputOptionsAndTickets();
            })
        ));
    }
    
    protected function getTickets()
    {
        $output = $this->getOutput();
        
        try {
            return $this
                ->getTransport()
                ->getTickets($this->getProjectName(), $this->getOptions())
            ;
        }catch(AccessDeniedException $e){
            $output->writeln('<error>Access Denied - Credentials wrong</error>');
        }catch(NotFoundException $e){
            $output->writeln('<error>Page not found</error>');
        }catch(UnprocessableEntityException $e){
            $output->writeln('<error>Unprocessable Entity</error>');
            $output->writeln($e->getMessage());
        }catch(InvalidStatusCodeException $e){
            $output->writeln('<error>StatusCode invalid</error>');
        }
        
        return array();
    }
    
    protected function outputTickets(array $tickets)
    {
        /* @var $ticket TicketObject */
        foreach($tickets as $ticket){
            $this->getOutput()->writeln(
                '<data>' .
                    'Ticket #' . $ticket->getId()." - \t" .
                    $ticket->getSummary() .
                '</data>'
            );
        }
    }
    
    protected function setOutputStyles()
    {
        parent::setOutputStyles();
        
        $colors = array('cyan', 'blue', 'magenta', 'red');
        
        foreach(TicketOptions::getPriorities() as $key => $priority){
            $style = new OutputFormatterStyle($colors[$key]);
            $this->getOutput()->getFormatter()->setStyle('prio-'.$priority, $style);
        }
    }
    
    /**
     * @return TicketOptions 
     */
    protected function getOptions()
    {
        if(false === $this->options){
            $this->options = $this->getDefaultOptions();
        }
        
        return $this->options;
    }
    
    protected function outputOptionsAndTickets()
    {
        $this->outputOptions();
        $this->outputTickets($this->getTickets());
    }
    
}