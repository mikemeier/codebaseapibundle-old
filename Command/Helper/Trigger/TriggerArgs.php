<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Helper\Trigger;

class TriggerArgs
{
    
    /**
     * @var TriggerInterface 
     */
    protected $trigger;
    
    /**
     * @var array 
     */
    protected $args = array();
    
    public function __construct(TriggerInterface $trigger, array $args)
    {
        $this->trigger = $trigger;
        $this->args = array_values($args);
    }
    
    /**
     * @param integer $number
     * @param mixed $default
     * @return mixed 
     */
    public function getArg($number, $default = null)
    {
        return isset($this->args[$number]) ? $this->args[$number] : $default;
    }
    
    /**
     * @return array 
     */
    public function getArgs()
    {
        return $this->args;
    }
    
    /**
     * @return TriggerInterface 
     */
    public function getTrigger()
    {
        return $this->trigger;
    }
    
}