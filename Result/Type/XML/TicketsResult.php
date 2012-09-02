<?php

namespace Ibrows\CodebaseApiBundle\Result\Type\XML;

use Ibrows\CodebaseApiBundle\Result\Type\AbstractXMLResult;
use Ibrows\CodebaseApiBundle\Result\TicketsResultInterface;
use Ibrows\CodebaseApiBundle\Result\ResultInterface;

use Ibrows\CodebaseApiBundle\Result\Object\Ticket\TicketObject;

use Ibrows\CodebaseApiBundle\Result\Object\Ticket\Mapper\ArrayMapper;

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