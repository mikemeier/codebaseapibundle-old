<?php

namespace Ibrows\CodebaseApiBundle\Command\Helper\Trigger;

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
    
}