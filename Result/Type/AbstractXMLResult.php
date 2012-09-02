<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result\Type;

use Ibrows\Bundle\CodebaseApiBundle\Result\AbstractResult;

abstract class AbstractXMLResult extends AbstractResult
{
    
    /**
     * @var SimpleXMLElement 
     */
    protected $xml;
    
    public function process()
    {
        $this->xml = simplexml_load_string($this->body);
    }
    
    /**
     * @return SimpleXMLElement 
     */
    public function getXML()
    {
        return $this->xml;
    }
    
}