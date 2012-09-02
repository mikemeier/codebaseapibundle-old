<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result\Type\JSON;

use Ibrows\Bundle\CodebaseApiBundle\Result\Type\AbstractJSONResult;
use Ibrows\Bundle\CodebaseApiBundle\Result\TicketsResultInterface;
use Ibrows\Bundle\CodebaseApiBundle\Result\ResultInterface;

use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\TicketObject;
use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\Mapper\ArrayMapper;

class TicketsResult extends AbstractJSONResult implements TicketsResultInterface
{
    
    /**
     * @return TicketObject[] $ticketObjects
     */
    public function getTickets()
    {
        $ticketData = array();
        
        foreach($this->getJSON() as $ticketBag){
            $ticketData[] = $ticketBag['ticket'];
        }
        
        $mapper = new ArrayMapper();
        return $mapper->map($ticketData, ResultInterface::RESULT_TYPE_JSON);
    }
    
}