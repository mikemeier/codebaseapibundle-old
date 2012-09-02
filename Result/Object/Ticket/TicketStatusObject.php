<?php

namespace Ibrows\CodebaseApiBundle\Result\Object\Ticket;

use Ibrows\CodebaseApiBundle\Result\Object\AbstractObject;

class TicketStatusObject extends AbstractObject
{
    
    protected $id, $name, $colour, $order, $treatAsClosed;

    /**
     * @param integer $id
     * @param string $name
     * @param string $colour
     * @param integer $order
     * @param boolean $treatAsClosed 
     */
    public function __construct($id, $name, $colour, $order, $treatAsClosed)
    {
        $this->id = (int)$id;
        $this->name = (string)$name;
        $this->colour = (string)$colour;
        $this->order = (int)$order;
        $this->treatAsClosed = (bool)$treatAsClosed;
    }
    
    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getColor()
    {
        return $this->colour;
    }
    
    /**
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * @return boolean
     */
    public function getTreatAsClosed()
    {
        return $this->treatAsClosed;
    }
    
}