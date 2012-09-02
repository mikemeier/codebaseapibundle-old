<?php

namespace Ibrows\CodebaseApiBundle\Result\Object\Ticket;

use Ibrows\CodebaseApiBundle\Result\Object\AbstractObject;

class TicketObject extends AbstractObject
{
    
    protected $id, $projectId, $summary, $type, $assignee, $reporter;
    
    /**
     * @var TicketCategoryObject 
     */
    protected $category;
    
    /**
     * @var TicketPriorityObject 
     */
    protected $priority;
    
    /**
     * @var TicketStatusObject 
     */
    protected $status;
    
    /**
     * @var \DateTime 
     */
    protected $updatedAt;
    
    /**
     * @var \DateTime 
     */
    protected $createdAt;
    
    /**
     * @param integer $id
     * @param integer $projectId
     * @param string $summary
     * @param string $type
     * @param string $assignee
     * @param string $reporter
     * @param TicketCategoryObject $category
     * @param TicketPriorityObject $priority
     * @param TicketStatusObject $status
     * @param \DateTime $updatedAt
     * @param \DateTime $createdAt 
     */
    public function __construct(
        $id, 
        $projectId,
        $summary, 
        $type, 
        $assignee, 
        $reporter,
        
        TicketCategoryObject $category,
            
        TicketPriorityObject $priority,
            
        TicketStatusObject $status,
            
        \DateTime $updatedAt,
        \DateTime $createdAt
    ){
        $this->id = (int)$id;
        $this->projectId = (int)$projectId;
        $this->summary = (string)$summary;
        $this->type = (string)$type;
        $this->assignee = (string)$assignee;
        $this->reporter = (string)$reporter;
        
        $this->category = $category;
        
        $this->priority = $priority;
        
        $this->status = $status;
        
        $this->updatedAt = $updatedAt;
        $this->createdAt = $createdAt;
    }
    
    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }
    
    /**
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @return string 
     */
    public function getAssignee()
    {
        return $this->assignee;
    }
    
    /**
     * @return string 
     */
    public function getReporter()
    {
        return $this->reporter;
    }
    
    /**
     * @return TicketCategoryObject 
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * @return TicketPriorityObject 
     */
    public function getPriority()
    {
        return $this->priority;
    }
    
    /**
     * @return TicketStatusObject 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }    
}