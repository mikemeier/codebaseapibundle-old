<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result;

use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\TicketObject;

interface TicketsResultInterface
{
    
    /**
     * @return TicketObject[] $tickets
     */
    public function getTickets();
    
}