<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Command\Shortcut;

use Ibrows\Bundle\CodebaseApiBundle\Command\AbstractCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class ShortcutCommand extends AbstractCommand
{
    
    protected function configure()
    {
        parent::configure();
        
        $this
            ->setName('codebase:shortcut')
            ->setDescription('Executes a saved shortcut')
        
            ->addArgument('shortcut', InputArgument::REQUIRED, 'Shortcut name')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        
        $shortcutStore = $this->getShortcutStore();
        $shortcutInput = $shortcutStore->get($input->getArgument('shortcut'));
        
        if(!$shortcutInput){
            $output->writeln('<error>Shortcut "'. $input->getArgument('shortcut') .'" not found</error>');
            
            $availableShortcuts = array_keys($shortcutStore->getData());
            
            if(count($availableShortcuts) == 0){
                $output->writeln('<info>No shortcuts available</info>');
                return;
            }
            
            $output->writeln('<info>Available:</info>');
            foreach($availableShortcuts as $availableShortcut){
               $output->writeln('<info>- <comment>'. $availableShortcut .'</comment></info>');
            }
            
            return;
        }
        
        /* @var $shortcutInput InputInterface */
        $shortcutInput = @unserialize($shortcutInput);
        
        if(false === $shortcutInput){
            $output->writeln('<error>Credentials wrong, couldnt decrypt shortcut</error>');
            return;
        }
        
        $command = $this->getApplication()->find($shortcutInput->getArgument('command'));
        
        if(!$command){
            $output->writeln('<error>Command "'. $command .'" not found</error>');
            return;
        }
        
        $arguments = array();
        foreach($shortcutInput->getArguments() as $key => $value){
            $arguments[$key] = $value;
        }
        
        foreach($shortcutInput->getOptions() as $key => $value){
            $arguments['--'. $key] = $value;
        }
        
        return $command->run(new ArrayInput($arguments), $output);
    }
}