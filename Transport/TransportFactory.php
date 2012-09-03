<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Transport;

use Ibrows\Bundle\CodebaseApiBundle\Credentials\Credentials;
use Ibrows\Bundle\CodebaseApiBundle\Result\ResultFactory;

class TransportFactory
{
    
    /**
     * @var string 
     */
    protected $type;
    
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
    public function __construct($type, $baseUri, ResultFactory $resultFactory)
    {
        $this->type = $type;
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
    public function getInstance(Credentials $credentials)
    {
        $className = __NAMESPACE__ .'\\Adapter\\'. ucfirst($this->type) .'Adapter';
        if(!class_exists($className)){
            throw new \InvalidArgumentException('Transport "'. $className .'" not found');
        }
        
        return new $className($this->baseUri, $credentials, $this->resultFactory);
    }
    
}