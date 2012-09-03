<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Transport;

use Ibrows\Bundle\CodebaseApiBundle\Credentials\Credentials;
use Ibrows\Bundle\CodebaseApiBundle\Query\Ticket\TicketOptions;
use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\TicketObject;
use Ibrows\Bundle\CodebaseApiBundle\Result\ResultFactory;

interface TransportInterface
{
    
    const STATUS_COMPLETED = '1507781';
    
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
    
    /**
     * @param string $projectName
     * @param integer $ticketId
     * @param string $message
     * @return boolean
     */
    public function closeTicket($projectName, $ticketId, $message);
    
}