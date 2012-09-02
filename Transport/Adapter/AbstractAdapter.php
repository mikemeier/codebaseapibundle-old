<?php

namespace Ibrows\CodebaseApiBundle\Transport\Adapter;

use Ibrows\CodebaseApiBundle\Credentials\Credentials;
use Ibrows\CodebaseApiBundle\Transport\TransportInterface;

use Ibrows\CodebaseApiBundle\Result\ResultInterface;
use Ibrows\CodebaseApiBundle\Result\ResultFactory;

use Ibrows\CodebaseApiBundle\Transport\Exception\UnprocessableEntityException;
use Ibrows\CodebaseApiBundle\Transport\Exception\InvalidStatusCodeException;
use Ibrows\CodebaseApiBundle\Transport\Exception\NotFoundException;
use Ibrows\CodebaseApiBundle\Transport\Exception\AccessDeniedException;

use Ibrows\CodebaseApiBundle\Result\Object\Ticket\TicketObject;
use Ibrows\CodebaseApiBundle\Query\Ticket\TicketOptions;

abstract class AbstractAdapter implements TransportInterface
{
    
    /**
     * @var string 
     */
    protected $baseUri;
    
    /**
     * @var Credentials 
     */
    protected $credentials;
    
    /**
     * @var ResultFactory 
     */
    protected $resultFactory;

    /**
     * @param string $baseUri
     * @param Credentials $credentials 
     */
    public function __construct($baseUri, Credentials $credentials, ResultFactory $resultFactory)
    {
        $this->baseUri = $baseUri;
        $this->credentials = $credentials;
        $this->resultFactory = $resultFactory;
    }
    
    /**
     * @return string 
     */
    public function getName()
    {
        return str_replace(__NAMESPACE__.'\\', '', get_class($this));
    }
    
    /**
     * @return ResultFactory 
     */
    public function getResultFactory()
    {
        return $this->resultFactory;
    }
    
    /**
     * @param string $projectName
     * @param integer $ticketId 
     * @return TicketObject
     */
    public function getTicketById($projectName, $ticketId)
    {
        $options = new TicketOptions();
        $options->setId($ticketId);
        
        $tickets = $this->getTickets($projectName, $options);
        
        return isset($tickets[0]) ? $tickets[0] : null;
    }
    
    /**
     * @param string $resultType
     * @param integer $statusCode
     * @param string $body
     * @return ResultInterface
     * @throws AccessDeniedException
     * @throws UnprocessableEntityException
     * @throws InvalidStatusCodeException
     * @throws NotFoundException 
     */
    protected function getResult($resultType, $statusCode, $body)
    {
        switch($statusCode){
            case 200:
                return $this->resultFactory->getInstance($resultType, $body);
            break;
            case 401:
                throw new AccessDeniedException($body);
            break;
            case 404:
                throw new NotFoundException($body);
            break;
            case 422:
                throw new UnprocessableEntityException($body);
            break;
            default:
                throw new InvalidStatusCodeException($body);
            break;
        }
    }
    
    /**
     * @return Credentials 
     */
    protected function getCredentials()
    {
        return $this->credentials;
    }
    
    /**
     * @return string 
     */
    protected function getBaseUri()
    {
        return $this->baseUri;
    }
    
}