<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Helper\Trigger;

use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\LoopAndReadHelper;

interface TriggerInterface
{
    
    /**
     * @param string $name 
     * @return boolean
     */
    public function isMatching($name);
    
    /**
     * @retun TriggerInterface
     */
    public function run();
    
    /**
     * @return string $name 
     */
    public function getName();
    
    /**
     * @param LoopAndReadHelper
     * @return TriggerInterface
     */
    public function setLoopAndReadHelper(LoopAndReadHelper $helper);
    
    /**
     * @return LoopAndReadHelper 
     */
    public function getLoopAndReadHelper();
    
}