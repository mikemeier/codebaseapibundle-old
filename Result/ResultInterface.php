<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result;

interface ResultInterface
{
  
    const 
        RESULT_TYPE_XML = 'XML',
        RESULT_TYPE_JSON = 'JSON';
    
    /**
     * @param string $body 
     */
    public function setBody($body);
    
    public function process();
    
}