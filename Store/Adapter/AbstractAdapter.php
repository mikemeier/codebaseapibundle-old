<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Store\Adapter;

use Ibrows\Bundle\CodebaseApiBundle\Store\StoreInterface;

abstract class AbstractAdapter implements StoreInterface
{
    
    /**
     * @var boolean 
     */
    protected $data = array();
    
    /**
     * @return array 
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @param string $key
     * @param mixed $data 
     * @return StoreInterface
     */
    public function set($key, $data)
    {
        $this->data[$key] = $data;
        
        return $this;
    }
    
    /**
     * @param string $key
     * @param mixed $default 
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if(is_null($key)){
            return $this->data;
        }
        
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
    
    public function remove($key)
    {
        if(isset($this->data[$key])){
            unset($this->data[$key]);
        }
        
        return $this;
    }
    
}