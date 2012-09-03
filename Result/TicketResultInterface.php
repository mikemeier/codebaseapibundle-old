<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result;

use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\TicketObject;

interface TicketResultInterface
{
    
    /**
     * @return TicketObject
     */
    public function getTicket();
    
}