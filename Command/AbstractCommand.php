<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command;

use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\LoopAndReadHelper;
use Ibrows\Bundle\CodebaseApiBundle\StoreAndEncryption\StoreAndEncryption;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class AbstractCommand extends ContainerAwareCommand
{

    /**
     * @var InputInterface 
     */
    protected $input;
    
    /**
     * @var OutputInterface 
     */
    protected $output;
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        
        $this->setOutputStyles();     
    }
    
    /**
     * @return LoopAndReadHelper 
     */
    protected function getNewLoopAndReaderHelper()
    {
        $helper = new LoopAndReadHelper(
            $this->getOutput(),
            $this->getContainer()->getParameter('ibrows_codebase_api.loopinterval')
        );
        $helper->setTriggers($this->getTriggers());
        
        return $helper;
    }
    
    /**
     * @return InputInterface 
     */
    protected function getInput()
    {
        return $this->input;
    }
    
    /**
     * @return OutputInterface 
     */
    protected function getOutput()
    {
        return $this->output;
    }
    
    protected function setOutputStyles()
    {
        $style = new OutputFormatterStyle('cyan');
        $this->getOutput()->getFormatter()->setStyle('data', $style);
    }
    
    /**
     * @return string 
     */
    protected function getCredentialsKey()
    {
        return $this->getContainer()->getParameter('ibrows.codebaseapi.credentials.key');
    }
    
    /**
     * @return StoreAndEncryption 
     */
    protected function getCredentialsStore()
    {
        return $this->getContainer()->get('ibrows.codebaseapi.storeandecryption.credential');
    }
    
    /**
     * @return StoreAndEncryption 
     */
    protected function getShortcutStore()
    {
        return $this->getContainer()->get('ibrows.codebaseapi.storeandecryption.shortcut');
    }
    
    /**
     * @return TriggerInterface[] $triggers 
     */
    protected function getTriggers()
    {
        return array();
    }
    
}