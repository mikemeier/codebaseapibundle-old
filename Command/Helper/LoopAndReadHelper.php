<?php

namespace Ibrows\CodebaseApiBundle\Command\Helper;

use Ibrows\CodebaseApiBundle\Command\Helper\Trigger\TriggerInterface;

use Symfony\Component\Console\Output\OutputInterface;

class LoopAndReadHelper
{
    
    /**
     * @var integer $loopInterval 
     */
    protected $loopInterval = 10;
    
    /**
     * @var \Closure 
     */
    protected $loopClosure;
    
    /**
     * @var TriggerInterface[] $triggers 
     */
    protected $triggers = array();  
    
    /**
     * @var OutputInterface 
     */
    protected $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }
    
    /**
     * @param type $interval
     * @return LoopAndReadHelper 
     */
    public function setLoopInterval($interval)
    {
        $this->loopInterval = (int)$interval;
        
        return $this;
    }
    
    /**
     * @param \Closure $closure
     * @return LoopAndReadHelper 
     */
    public function setLoopClosure(\Closure $closure)
    {
        $this->loopClosure = $closure;
        
        return $this;
    }
    
    /**
     * @param TriggerInterface $trigger 
     * @return LoopAndReadHelper
     */
    public function addTrigger(TriggerInterface $trigger)
    {
        $this->triggers[] = $trigger;
        
        return $this;
    }
    
    /**
     * @param TriggerInterface[] $triggers 
     * @return LoopAndReadHelper
     */
    public function setTriggers(array $triggers)
    {
        $this->triggers = array();
        
        foreach($triggers as $trigger){
            $this->addTrigger($trigger);
        }
        
        return $this;
    }
    
    /**
     * @param string $input 
     */
    protected function runTriggers($input)
    {
        $found = false;
        $allowedCommands = array();
        
        /* @var $trigger TriggerInterface */
        foreach($this->triggers as $trigger){
            $allowedCommands[] = $trigger->getName();
            if(true === $trigger->isMatching($input)){
                $trigger->run();
                $found = true;
            }
        }
        
        if(false === $found){
            $this->output->writeln('<error>Command "'. $input .'" not found</error>');
            $this->output->writeln('<info>Try: '. implode(", ", $allowedCommands) .'</info>');
        }
    }
    
    public function run()
    {
        $ticks = $this->loopInterval;
        
        $nonBlocking = stream_set_blocking(STDIN, 0);
        if(false === $nonBlocking){
            $this->output->writeln('<info>Automatic refresh disabled because stream_set_blocking is not working on this OS - try *nix Servers</info>');
            $this->output->writeln('<info>See https://bugs.php.net/bug.php?id=47918</info>');
        }
        
        while(true){
            
            if($ticks % $this->loopInterval == 0){
                $closure = $this->loopClosure;
                $closure();
            }
            
            $input = fgets(STDIN, 2048);
            if($input){
                $this->runTriggers(trim($input));
            }
            
            $ticks++;
            
            sleep(1);
        }
    }
    
}