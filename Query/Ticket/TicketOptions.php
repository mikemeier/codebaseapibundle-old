<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Query\Ticket;

class TicketOptions
{
    
    const 
        ASSIGNEE_ME = 'me';
    
    const 
        RESOLUTION_CLOSED = 'closed',
        RESOLUTION_OPEN = 'open';
    
    const 
        SORT_PRIORITY = 'priority',
        SORT_UPDATED_AT = 'updated_at';
    
    const 
        ORDER_DESC = 'desc',
        ORDER_ASC = 'asc';
    
    const 
        PRIORITY_LOW = 'low',
        PRIORITY_NORMAL = 'normal',
        PRIORITY_HIGH = 'high',
        PRIORITY_CIRITAL = 'critical';
    
    const 
        QUERY_OPTIONS_SEPARATOR = '%20';
    
    // assignee:me%20resolution:closed%20sort:updated_at%20order:desc
    
    protected $options = array();
    
    /**
     * @param string $assignee
     * @return TicketOptions 
     */
    public function setAssignee($assignee)
    {
        $this->options['assignee'] = $assignee;
        
        return $this;
    }
    
    public function removeAssignee()
    {
        unset($this->options['assignee']);
        
        return $this;
    }
    
    public static function getPriorities()
    {
        return array(
            self::PRIORITY_LOW,
            self::PRIORITY_NORMAL,
            self::PRIORITY_HIGH,
            self::PRIORITY_CIRITAL
        );
    }
    
    /**
     * @param string $assignee
     * @return TicketOptions 
     */
    public function setResolution($resultion)
    {
        $this->options['resolution'] = $resultion;
        
        return $this;
    }
    
    /**
     * @param integer $ticketId
     * @return TicketOptions 
     */
    public function setId($ticketId)
    {
        $this->options['id'] = $ticketId;
        
        return $this;
    }
    
    /**
     * @param string $assignee
     * @return TicketOptions 
     */
    public function setUpdatedDate(\DateTime $date)
    {
        $this->options['update'] = $date->format('Y-m-d');
        
        return $this;
    }
    
    /**
     * @param string $assignee
     * @return TicketOptions 
     */
    public function setSort($sort)
    {
        $this->options['sort'] = $sort;
        
        return $this;
    }
    
    /**
     * @param string $assignee
     * @return TicketOptions 
     */
    public function setOrder($order)
    {
        $this->options['order'] = $order;
        
        return $this;
    }
    
    /**
     * @return string 
     */
    public function getQuery($separator = self::QUERY_OPTIONS_SEPARATOR)
    {
        $queryPieces = array();
        
        foreach($this->options as $key => $value){
            $queryPieces[] = $key.':"'. $value .'"';
        }
        
        return implode($separator, $queryPieces);
    }
    
}