<?php

namespace Ibrows\CodebaseApiBundle\Result;

use Ibrows\CodebaseApiBundle\Result\Object\Ticket\TicketObject;

interface TicketsResultInterface
{
    
    /**
     * @return TicketObject[] $tickets
     */
    public function getTickets();
    
}