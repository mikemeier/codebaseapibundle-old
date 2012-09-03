<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command;

use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\LoopAndReadHelper;
use Ibrows\Bundle\CodebaseApiBundle\Credentials\Credentials;
use Ibrows\Bundle\CodebaseApiBundle\Store\StoreInterface;

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
    
    /**
     * @var Credentials 
     */
    protected $credentials;
    
    protected function configure()
    {
        $this
            ->addOption('recordname', 'r', InputOption::VALUE_REQUIRED, 'Records the current input')
            ->addOption('projectname', 'p', InputOption::VALUE_REQUIRED, 'Codebase Project Name')
        ;
    }
    
    /**
     * @param InputInterface $input
     * @param OutputInterface $output 
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        
        $this->credentials = new Credentials(
            $this->getContainer()->getParameter('ibrows_codebase_api.credentials.user'), 
            $this->getContainer()->getParameter('ibrows_codebase_api.credentials.key')
        );
        
        $this->setOutputStyles();
        
        $this->saveInputIfOptionIsSet();
    }
    
    /**
     * @return string 
     */
    protected function getProjectName()
    {
        $option = $this->getInput()->getOption('projectname');
        if($option){
            return $option;
        }
        
        $projectName = $this->getContainer()->getParameter('ibrows_codebase_api.projectname');
        
        if(!$projectName){
            throw new \InvalidArgumentException("Need projectname option or 'ibrows_codebase_api.projectname' Parameter (parameters.yml e.g.)");
        }
        
        return $projectName;
    }
    
    /**
     * @return Credentials 
     */
    protected function getCredentials()
    {
        return $this->credentials;
    }
    
    protected function saveInputIfOptionIsSet()
    {
        $input = $this->getInput();
        $saveInputname = $input->getOption('recordname');
        
        if(!$saveInputname){
            return;
        }

        $input->setOption('recordname', false);

        $arguments = array();
        foreach($input->getArguments() as $key => $value){
            $arguments[$key] = $value;
        }

        $saveInput = new ArrayInput($arguments, $this->getDefinition());

        foreach($input->getOptions() as $key => $value){
            $saveInput->setOption($key, $value);
        }

        $data = serialize($saveInput);

        $shortcutStore = $this->getShortcutStore();
        $shortcutStore->set($saveInputname, $data);
        $shortcutStore->flush();

        $this->getOutput()->writeln('<info>Shortcut "'. $saveInputname .'" saved</info>');
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
     * @return StoreInterface 
     */
    protected function getShortcutStore()
    {
        return $this->getContainer()->get('ibrows.codebaseapi.shurtcutstore');
    }
    
    /**
     * @return TriggerInterface[] $triggers 
     */
    protected function getTriggers()
    {
        return array();
    }
    
}