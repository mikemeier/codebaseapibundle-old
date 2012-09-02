<?php

namespace Ibrows\CodebaseApiBundle\Result\Object\Ticket\Mapper;

use Ibrows\CodebaseApiBundle\Result\ResultInterface;

use Ibrows\CodebaseApiBundle\Result\Object\Ticket\TicketObject;

use Ibrows\CodebaseApiBundle\Result\Object\Ticket\TicketCategoryObject;
use Ibrows\CodebaseApiBundle\Result\Object\Ticket\TicketPriorityObject;
use Ibrows\CodebaseApiBundle\Result\Object\Ticket\TicketStatusObject;

class ArrayMapper
{
    
    /**
     * @param array $data
     * @param string $resultType
     * @return TicketObject[] $tickets
     */
    public function map(array $data, $resultType)
    {
        $tickets = array();
        
        $self = $this;
        $m = function($field) use ($self, $resultType){
            return $self->getMapping($field, $resultType);
        };
        
        foreach($data as $ticketData){
            $ticketData = (array)$ticketData;
            
            $categoryData = (array)$ticketData[$m('category')];
            $priorityData = (array)$ticketData[$m('priority')];
            $statusData = (array)$ticketData[$m('status')];
            
            $tickets[] = new TicketObject(
                $ticketData[$m('ticket-id')], 
                $ticketData[$m('project-id')], 
                $ticketData[$m('summary')], 
                $ticketData[$m('ticket-type')], 
                $ticketData[$m('assignee')], 
                $ticketData[$m('reporter')], 
                    
                new TicketCategoryObject(
                    $categoryData[$m('id')], 
                    $categoryData[$m('name')]
                ), 
                    
                new TicketPriorityObject(
                    $priorityData[$m('id')], 
                    $priorityData[$m('name')], 
                    $priorityData[$m('colour')], 
                    $priorityData[$m('default')], 
                    $priorityData[$m('position')]
                ), 
                    
                new TicketStatusObject(
                    $statusData[$m('id')], 
                    $statusData[$m('name')], 
                    $statusData[$m('colour')], 
                    $statusData[$m('order')], 
                    $statusData[$m('treat-as-closed')]
                ), 
                    
                new \DateTime($ticketData[$m('updated-at')]), 
                new \DateTime($ticketData[$m('created-at')])
            );
        }
        
        return $tickets;
    }
    
    protected function getMapping($field, $resultType)
    {
        if($field == "treat-as-closed"){
            return $field;
        }
        
        switch($resultType){
            case ResultInterface::RESULT_TYPE_XML:
                return $field;
            break;
            case ResultInterface::RESULT_TYPE_JSON:
                return str_replace("-", "_", $field);
            break;
        }
        
        return $field;
    }
    
}

/*
JSON
[19] =>
  array(21) {
    'ticket_id' =>
    int(92)
    'summary' =>
    string(12) "How it Works"
    'ticket_type' =>
    string(7) "Feature"
    'reporter_id' =>
    int(17647)
    'assignee_id' =>
    int(38661)
    'assignee' =>
    string(10) "mike.meier"
    'reporter' =>
    string(11) "jonas.hager"
    'category_id' =>
    int(1507791)
    'category' =>
    array(2) {
      'id' =>
      int(1507791)
      'name' =>
      string(7) "General"
    }
    'priority_id' =>
    int(1507787)
    'priority' =>
    array(5) {
      'id' =>
      int(1507787)
      'name' =>
      string(6) "Normal"
      'colour' =>
      string(4) "blue"
      'default' =>
      bool(true)
      'position' =>
      int(3)
    }
    'status_id' =>
    int(1507778)
    'status' =>
    array(5) {
      'id' =>
      int(1507778)
      'name' =>
      string(3) "New"
      'colour' =>
      string(5) "green"
      'order' =>
      int(1)
      'treat-as-closed' =>
      bool(false)
    }
    'milestone_id' =>
    NULL
    'milestone' =>
    NULL
    'deadline' =>
    NULL
    'tags' =>
    string(0) ""
    'updated_at' =>
    string(20) "2012-08-30T11:36:19Z"
    'created_at' =>
    string(20) "2012-08-30T11:36:19Z"
    'estimated_time' =>
    NULL
    'project_id' =>
    int(29953)
  }
  
  [19] => SimpleXMLElement Object
(
    [ticket-id] => 92
    [summary] => How it Works
    [ticket-type] => Feature
    [reporter-id] => 17647
    [assignee-id] => 38661
    [assignee] => mike.meier
    [reporter] => jonas.hager
    [category-id] => 1507791
    [category] => SimpleXMLElement Object
        (
            [id] => 1507791
            [name] => General
        )

    [priority-id] => 1507787
    [priority] => SimpleXMLElement Object
        (
            [id] => 1507787
            [name] => Normal
            [colour] => blue
            [default] => true
            [position] => 3
        )

    [status-id] => 1507778
    [status] => SimpleXMLElement Object
        (
            [id] => 1507778
            [name] => New
            [colour] => green
            [order] => 1
            [treat-as-closed] => false
        )

    [milestone-id] => SimpleXMLElement Object
        (
            [@attributes] => Array
                (
                    [nil] => true
                )

        )

    [milestone] => SimpleXMLElement Object
        (
            [@attributes] => Array
                (
                    [nil] => true
                )

        )

    [deadline] => SimpleXMLElement Object
        (
            [@attributes] => Array
                (
                    [nil] => true
                )

        )

    [tags] => SimpleXMLElement Object
        (
        )

    [updated-at] => 2012-08-30T11:36:19Z
    [created-at] => 2012-08-30T11:36:19Z
    [estimated-time] => SimpleXMLElement Object
        (
            [@attributes] => Array
                (
                    [nil] => true
                )

        )

    [project-id] => 29953
)

)*/