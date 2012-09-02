<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Credentials;

class Credentials
{
    /**
     * @var string 
     */
    protected $username;
    
    /**
     * @var string 
     */
    protected $key;
    
    public function __construct($username, $key)
    {
        $this->username = $username;
        $this->key = $key;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getKey()
    {
        return $this->key;
    }
    
}