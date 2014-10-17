<?php

namespace Vivait\TenantBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vivait_tenant');

        $rootNode
            ->children()
                ->arrayNode('tenant')
                    ->children()
                        ->scalarNode('name')->end()
                        ->arrayNode('attributes')
                            ->isRequired()
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')
                            ->defaultValue([])
                            ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}