<?php

namespace Ibrows\CodebaseApiBundle\Command\Helper\Trigger;

class ClosureTrigger extends AbstractTrigger
{
    
    /**
     * @var string 
     */
    protected $pattern;
    
    /**
     * @var \Closure 
     */
    protected $trigger;
    
    /**
     * @var array 
     */
    protected $args = array();
    
    /**
     * @param string $pattern
     * @param \Closure $trigger 
     */
    public function __construct($pattern, \Closure $trigger)
    {
        $this->pattern = $pattern;
        $this->trigger = $trigger;
    }
    
    /**
     * @return string 
     */
    public function getName()
    {
        return $this->pattern;
    }
    
    /**
     * @param string $name 
     * @return boolean
     */
    public function isMatching($name){
        $matches = array();
        if(preg_match($this->pattern, $name, $matches)){
            unset($matches[0]);
            $this->args = $matches;
                    
            return true;
        }
        
        return false;
    }
    
    /**
     * @param array $args
     * @return ClosureTrigger 
     */
    public function run(){
        call_user_func_array($this->trigger, $this->args);
        
        return $this;
    }
    
}