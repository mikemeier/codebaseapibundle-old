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
                ->scalarNode('uri')
                    ->defaultValue('http://api3.codebasehq.com/')
                ->end()
                ->arrayNode('store_credential')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->defaultValue('Ibrows\\Bundle\\CodebaseApiBundle\\Store\\Adapter\\FileAdapter')
                        ->end()
                        ->scalarNode('path')
                            ->defaultValue('%kernel.root_dir%/cache/codebaseapi/credential.txt')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('store_shortcut')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->defaultValue('Ibrows\\Bundle\\CodebaseApiBundle\\Store\\Adapter\\FileAdapter')
                        ->end()
                        ->scalarNode('path')
                            ->defaultValue('%kernel.root_dir%/cache/codebaseapi/shortcut.txt')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('encryption')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->defaultValue('Ibrows\\Bundle\\CodebaseApiBundle\\Encryption\\Adapter\\McryptAdapter')
                        ->end()
                        ->scalarNode('cypher')
                            ->defaultValue(MCRYPT_RIJNDAEL_256)
                        ->end()
                        ->scalarNode('mode')
                            ->defaultValue(MCRYPT_MODE_ECB)
                        ->end()
                        ->scalarNode('iv_mode')
                            ->defaultValue(MCRYPT_RAND)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('store_and_encryption')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->defaultValue('Ibrows\\Bundle\\CodebaseApiBundle\\StoreAndEncryption\\StoreAndEncryption')
                        ->end()
                    ->end() 
                ->end()
                ->arrayNode('result_factory')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')
                            ->defaultValue('XML')
                        ->end()
                    ->end() 
                ->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
}