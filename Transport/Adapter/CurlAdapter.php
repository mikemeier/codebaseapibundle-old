<?php

namespace Ibrows\CodebaseApiBundle\Transport\Adapter;

use Ibrows\CodebaseApiBundle\Result\Object\Ticket\TicketObject;
use Ibrows\CodebaseApiBundle\Query\Ticket\TicketOptions;
use Ibrows\CodebaseApiBundle\Transport\Exception\NotFoundException;

class CurlAdapter extends AbstractAdapter
{
    
    protected $handle = false;
    
    public function __destruct()
    {
        if(is_resource($this->handle)){
            curl_close($this->handle);
        }
    }
    
    /**
     * @param array $options
     * @param TicketOptions
     * @return NoteObject[] $notes
     */
    public function getNotes($projectName, $ticketId)
    {
        
    }
    
    /**
     * @param array $options
     * @param TicketOptions
     * @return TicketObject[] $tickets
     */
    public function getTickets($projectName, TicketOptions $options)
    {
        $page = 1;
        $tickets = array();
        
        while(true){
            try {
                $ch = $this->getCurlHandle();
        
                curl_setopt($ch, CURLOPT_URL, $this->getBaseUri() . $projectName.'/tickets?page='. $page .'&query='. $options->getQuery());
                
                $result = curl_exec($ch);
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $newTickets = $this->getResult('Tickets', $statusCode, $result)->getTickets();
                
                $tickets = array_merge($tickets, $newTickets);
            }catch(NotFoundException $e){
                return $tickets;
            }
            
            $page++;
        }
    }
    
    /**
     * @param boolean $new
     * @return resource 
     */
    protected function getCurlHandle($new = false)
    {
        if(false !== $this->handle && false === $new){
            return $this->handle;
        }
        
        $ch = curl_init(); 
        
        $credentials = $this->getCredentials();
        
        $headers = array();
        foreach($this->getResultFactory()->getRequestHeaders() as $key => $value){
            $headers[] = $key.': '. $value;
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $credentials->getUsername() .':'. $credentials->getKey());
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        return $this->handle = $ch;
    }
    
}