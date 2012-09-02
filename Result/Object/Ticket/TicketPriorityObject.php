<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result\Object\Ticket;

use Ibrows\Bundle\CodebaseApiBundle\Result\Object\AbstractObject;

class TicketPriorityObject extends AbstractObject
{
    
    protected $id, $name, $colour, $default, $position;
    
    /**
     * @param integer $id
     * @param string $name
     * @param string $colour
     * @param string $default
     * @param integer $position 
     */
    public function __construct(
        $id,
        $name,
        $colour,
        $default,
        $position
    ){
        $this->id = (int)$id;
        $this->name = (string)$name;
        $this->colour = (string)$colour;
        $this->default = (string)$default;
        $this->position = (int)$position;
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
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }
    
    /**
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }
    
}