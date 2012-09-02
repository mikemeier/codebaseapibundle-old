<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result\Object;

abstract class AbstractObject implements ObjectInterface
{
    
    public function getUniqueId()
    {
        $string = '';
        
        foreach(get_class_methods($this) as $methodName){
            if(substr($methodName, 0, 3) !== 'get' || $methodName == 'getUniqueId'){
                continue;
            }
            
            $value = $this->$methodName();
            
            if(!is_string($value) && !is_int($value) && !is_bool($value) && !$value instanceof ObjectInterface){
                continue;
            }
            
            if($value instanceof ObjectInterface){
                $string .= (string)$value->getUniqueId();
                continue;
            }
            
            $string .= (string)$value;
        }
        
        return $string;
    }
    
}