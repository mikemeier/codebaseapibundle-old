<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result\Type;

use Ibrows\Bundle\CodebaseApiBundle\Result\AbstractResult;

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