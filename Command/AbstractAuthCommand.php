<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command;

use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\Trigger\TriggerInterface;
use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\Trigger\ClosureTrigger;
use Ibrows\Bundle\CodebaseApiBundle\Command\Helper\Trigger\TriggerArgs;

use Ibrows\Bundle\CodebaseApiBundle\Transport\TransportFactory;
use Ibrows\Bundle\CodebaseApiBundle\Transport\TransportInterface;
use Ibrows\Bundle\CodebaseApiBundle\Transport\Exception\AccessDeniedException;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Command\Command;

abstract class AbstractAuthCommand extends AbstractCommand
{
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->addArgument('projectname', InputArgument::REQUIRED, 'Codebase Project Name')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        
        $this->transport = $this->getTransportFactory()->getInstance(
            $this->getCredentials()
        );
    }
    
    /**
     * @return TransportFactory 
     */
    protected function getTransportFactory()
    {
        return $this->getContainer()->get('ibrows.codebaseapi.transportfactory');
    }
    
    /**
     * @return string 
     */
    protected function getProjectName()
    {
        return $this->getInput()->getArgument('projectname');
    }
    
    /**
     * @return TransportInterface 
     */
    protected function getTransport()
    {
        return $this->transport;
    }
    
    /**
     * @return array
     */
    protected function getDefaultNewCommandInputArgs(Command $command, array $merge = array())
    {
        return array_merge(array(
            'command' => $command->getName(),
            'projectname' => $this->getProjectName()
        ), $merge);
    }
    
    /**
     * @return TriggerInterface[] $triggers 
     */
    protected function getTriggers()
    {
        $self = $this;
        
        return array_merge(parent::getTriggers(), array(
            new ClosureTrigger('|^stop$|', function(TriggerArgs $args){
                $args->getTrigger()->getLoopAndReadHelper()->stop();
            }),
                    
            new ClosureTrigger('|^open (\d+)$|', function(TriggerArgs $args) use ($self){
                $command = $self->getApplication()->find('codebase:ticket:open-in-browser');
                
                $arguments = $self->getDefaultNewCommandInputArgs($command, array(
                    'ticketnr' => $args->getArg(0)
                ));

                $command->run(new ArrayInput($arguments), $self->getOutput());
            })
        ));
    }
    
}