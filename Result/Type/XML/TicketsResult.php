<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result\Type\XML;

use Ibrows\Bundle\CodebaseApiBundle\Result\Type\AbstractXMLResult;
use Ibrows\Bundle\CodebaseApiBundle\Result\TicketsResultInterface;
use Ibrows\Bundle\CodebaseApiBundle\Result\ResultInterface;

use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\TicketObject;

use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\Mapper\ArrayMapper;

class TicketsResult extends AbstractXMLResult implements TicketsResultInterface
{
    
    /**
     * @return TicketObject[] $ticketObjects
     */
    public function getTickets()
    {
        $ticketData = array();
        foreach($this->getXML()->ticket as $ticketBag){
            $ticketData[] = $ticketBag;
        }
        
        $mapper = new ArrayMapper();
        return $mapper->map($ticketData, ResultInterface::RESULT_TYPE_XML);
    }
    
}