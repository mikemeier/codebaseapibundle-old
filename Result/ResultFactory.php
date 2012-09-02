<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Result;

class ResultFactory
{
    
    protected $dataType;
    
    /**
     * @param string $dataType 
     */
    public function __construct($dataType)
    {
        $this->dataType = $dataType;
    }
    
    /**
     * @return string 
     */
    public function getDataType()
    {
        return $this->dataType;
    }
    
    public function getRequestHeaders()
    {
        switch($this->dataType){
            case 'XML':
                return array(
                    'content-type' => 'application/xml',
                    'accept' => 'application/xml'
                );
            break;
            case 'JSON':
                return array(
                    'content-type' => 'application/json',
                    'accept' => 'application/json'
                );
            break;
        }
        
        return array();
    }
    
    /**
     * @param string $resultType
     * @param string $body
     * @return ResultInterface
     * @throws \InvalidArgumentException 
     */
    public function getInstance($resultType, $body)
    {
        $className = __NAMESPACE__.'\\Type\\'. ucfirst($this->dataType) .'\\'. ucfirst($resultType) .'Result';
        if(!class_exists($className)){
            throw new \InvalidArgumentException('ResultType "'. $className .'" not found');
        }
        
        /* @var $result ResultInterface */
        $result = new $className();
        $result->setBody($body);
        $result->process();
        
        return $result;
    }
    
}