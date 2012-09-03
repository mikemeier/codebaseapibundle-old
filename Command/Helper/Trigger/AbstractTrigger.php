<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Helper\Trigger;

use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\LoopAndReadHelper;

abstract class AbstractTrigger implements TriggerInterface
{
    
    /**
     * @var LoopAndReadHelper 
     */
    protected $loopAndReadHelper;
    
    /**
     * @param LoopAndReadHelper $helper 
     * @return AbstractTrigger
     */
    public function setLoopAndReadHelper(LoopAndReadHelper $helper){
        $this->loopAndReadHelper = $helper;
        
        return $this;
    }
    
    /**
     * @return LoopAndReadHelper 
     */
    public function getLoopAndReadHelper()
    {
        return $this->loopAndReadHelper;
    }
    
}