<?php

namespace Ibrows\CodebaseApiBundle\Result\Type;

use Ibrows\CodebaseApiBundle\Result\AbstractResult;

abstract class AbstractJSONResult extends AbstractResult
{
    
    /**
     * @var array 
     */
    protected $json;
    
    public function process()
    {
        $this->json = json_decode($this->getBody(), true);
    }
    
    /**
     * @return array 
     */
    protected function getJSON()
    {
        return $this->json;
    }
    
}