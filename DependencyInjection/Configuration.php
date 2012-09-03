<?php

namespace Ibrows\Bundle\CodebaseApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ibrows_codebase_api');

        /* @var $rootNode ArrayNodeDefinition */
        $rootNode
            ->children()
                ->arrayNode('uri')
                    ->addDefaultsIfNotSet()
                    ->children()
                
                        ->scalarNode('api')
                            ->defaultValue('http://api3.codebasehq.com/')
                        ->end()
                
                        ->scalarNode('company')
                            ->isRequired()
                        ->end()
                
                    ->end()
                ->end()
                
                ->arrayNode('credentials')
                    ->addDefaultsIfNotSet()
                    ->children()
                
                        ->scalarNode('user')
                            ->isRequired()
                        ->end()
                
                        ->scalarNode('key')
                            ->isRequired()
                        ->end()
                
                    ->end()
                ->end()
                
                ->scalarNode('loopinterval')
                    ->defaultValue(20)
                ->end()
                
                ->scalarNode('projectname')
                    
                ->end()
                
                ->arrayNode('store')
                    ->addDefaultsIfNotSet()
                    ->children()
                
                        ->arrayNode('shortcut')
                            ->addDefaultsIfNotSet()
                            ->children()
                
                                ->scalarNode('class')
                                    ->defaultValue('Ibrows\\Bundle\\CodebaseApiBundle\\Store\\Adapter\\FileAdapter')
                                ->end()
                
                                ->scalarNode('path')
                                    ->defaultValue('%kernel.root_dir%/cache/codebaseapi/shortcuts.txt')
                                ->end()
                
                            ->end()
                        ->end()
                        
                    ->end()
                ->end()
                
                ->arrayNode('resultfactory')
                    ->addDefaultsIfNotSet()
                    ->children()
                
                        ->scalarNode('class')
                            ->defaultValue('Ibrows\\Bundle\\CodebaseApiBundle\\Result\\ResultFactory')
                        ->end()
                
                        ->scalarNode('type')
                            ->defaultValue('XML')
                        ->end()
                
                    ->end() 
                ->end()
                
                ->arrayNode('transportfactory')
                    ->addDefaultsIfNotSet()
                    ->children()
                
                        ->scalarNode('class')
                            ->defaultValue('Ibrows\\Bundle\\CodebaseApiBundle\\Transport\\TransportFactory')
                        ->end()
                
                        ->scalarNode('type')
                            ->defaultValue('curl')
                        ->end()

                    ->end() 
                ->end()
                
            ->end()
        ;
        
        return $treeBuilder;
    }
}