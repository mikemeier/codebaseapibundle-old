<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result\Type\XML;

use Ibrows\Bundle\CodebaseApiBundle\Result\Type\AbstractXMLResult;
use Ibrows\Bundle\CodebaseApiBundle\Result\TicketResultInterface;
use Ibrows\Bundle\CodebaseApiBundle\Result\ResultInterface;

use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\TicketObject;

use Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket\Mapper\ArrayMapper;

class TicketResult extends AbstractXMLResult implements TicketResultInterface
{
    
    /**
     * @return TicketObject
     */
    public function getTicket()
    {
        var_dump($this->xml);
        die;
        
        $ticketData = array();
        foreach($this->getXML()->ticket as $ticketBag){
            $ticketData[] = $ticketBag;
        }
        
        $mapper = new ArrayMapper();
        return $mapper->map(array($ticketData), ResultInterface::RESULT_TYPE_XML);
    }
    
}