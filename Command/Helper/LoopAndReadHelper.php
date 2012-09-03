<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Helper;

use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\Trigger\TriggerInterface;

use Symfony\Component\Console\Output\OutputInterface;

class LoopAndReadHelper
{
    
    /**
     * @var integer $loopInterval 
     */
    protected $loopInterval;
    
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
    
    /**
     * @var boolean 
     */
    protected $stop = false;

    /**
     * @param OutputInterface $output
     * @param integer $loopInterval
     * @throws \InvalidArgumentException 
     */
    public function __construct(OutputInterface $output, $loopInterval = 20)
    {
        $this->output = $output;
        $this->loopInterval = (int)$loopInterval;
        
        if($loopInterval < 10){
            throw new \InvalidArgumentException("LoopInterval has to be at minimum 10s");
        }
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
        $trigger->setLoopAndReadHelper($this);
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
        
        while(false === $this->stop){
            
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
    
    public function stop()
    {
        $this->stop = true;
    }
    
}