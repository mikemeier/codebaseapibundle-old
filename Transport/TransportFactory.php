<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Transport;

use Ibrows\Bundle\CodebaseApiBundle\Credentials\Credentials;
use Ibrows\Bundle\CodebaseApiBundle\Result\ResultFactory;

class TransportFactory
{
    
    /**
     * @var string 
     */
    protected $baseUri;
    
    /**
     * @var ResultFactory 
     */
    protected $resultFactory;

    /**
     * @param string $baseUri 
     */
    public function __construct($baseUri, ResultFactory $resultFactory)
    {
        $this->baseUri = $baseUri;
        $this->resultFactory = $resultFactory;
    }
    
    /**
     * @return string 
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }
    
    /**
     * @param string $type
     * @param Credentials $credentials
     * @return AuthInterface
     * @throws \InvalidArgumentException 
     */
    public function getInstance($type, Credentials $credentials)
    {
        $className = __NAMESPACE__ .'\\Adapter\\'. ucfirst($type) .'Adapter';
        if(!class_exists($className)){
            throw new \InvalidArgumentException('Transport "'. $className .'" not found');
        }
        
        return new $className($this->baseUri, $credentials, $this->resultFactory);
    }
    
}