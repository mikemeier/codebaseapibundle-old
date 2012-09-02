<?php

namespace Ibrows\CodebaseApiBundle\Transport;

use Ibrows\CodebaseApiBundle\Credentials\Credentials;
use Ibrows\CodebaseApiBundle\Query\Ticket\TicketOptions;
use Ibrows\CodebaseApiBundle\Result\Object\Ticket\TicketObject;
use Ibrows\CodebaseApiBundle\Result\ResultFactory;

interface TransportInterface
{
    
    /**
     * @param string $projectName
     * @param TicketOptions $options 
     * @return TicketObject[] $tickets
     */
    public function getTickets($projectName, TicketOptions $options);
    
    /**
     * @param string $projectName
     * @param integer $ticketId 
     * @return TicketObject
     */
    public function getTicketById($projectName, $ticketId);
    
    /**
     * @param array $options
     * @param TicketOptions
     * @return NoteObject[] $notes
     */
    public function getNotes($projectName, $ticketId);
    
    /**
     * @return string 
     */
    public function getName();
    
    /**
     * @return ResultFactory 
     */
    public function getResultFactory();
    
}