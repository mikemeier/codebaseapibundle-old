<?php

namespace Ibrows\CodebaseApiBundle\Store;

interface StoreInterface
{
    
    /**
     * @param string $key
     * @param mixed $data 
     * @return StoreInterface
     */
    public function set($key, $data);
    
    /**
     * @param string $key
     * @param mixed $default 
     * @return mixed
     */
    public function get($key, $default = null);
    
    /**
     * @return mixed 
     */
    public function getData();
    
    /**
     * @return StoreInterface 
     */
    public function flush();
    
    /**
     * @param string $key
     * @return StoreInterface
     */
    public function remove($key);
    
}