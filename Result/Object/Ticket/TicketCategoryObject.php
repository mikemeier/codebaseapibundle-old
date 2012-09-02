<?php

namespace Ibrows\CodebaseApiBundle\Result\Object\Ticket;

use Ibrows\CodebaseApiBundle\Result\Object\AbstractObject;

class TicketCategoryObject extends AbstractObject
{
    
    protected $id, $name;

    /**
     * @param integer $id
     * @param string $name 
     */
    public function __construct($id, $name)
    {
        $this->id = (int)$id;
        $this->name = (string)$name;
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
    
}