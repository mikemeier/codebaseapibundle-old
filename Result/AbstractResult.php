<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result;

abstract class AbstractResult implements ResultInterface
{
    
    /**
     * @var string 
     */
    protected $body;
    
    /**
     * @param string $body 
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
    
    /**
     * @return string 
     */
    protected function getBody()
    {
        return $this->body;
    }
    
}