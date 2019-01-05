<?php

namespace Ruwler\SdkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Ruwler\SdkBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->root('ruwler');

        $rootNode
            ->children()
                ->scalarNode('api_key')
                    ->beforeNormalization()
                        ->ifString()
                        ->then($this->getTrimClosure())
                    ->end()
                    ->defaultNull()
                ->end()
                ->scalarNode('auth_mode')
                    ->beforeNormalization()
                        ->ifString()
                        ->then($this->getTrimClosure())
                    ->end()
                    ->defaultValue('https')
                ->end()
                ->scalarNode('host')
                    ->beforeNormalization()
                        ->ifString()
                        ->then($this->getTrimClosure())
                    ->end()
                    ->defaultValue('https://api.ruwler.io')
                ->end()
                ->scalarNode('timeout')->defaultValue(30)->end()
                ->scalarNode('debug')->defaultFalse()->end()
                ->scalarNode('format')->defaultValue('json')->end()
        ;

        return $treeBuilder;
    }

    private function getTrimClosure(): callable
    {
        return function ($str) {
            $value = trim($str);
            if ($value === '') {
                return null;
            }
            return $value;
        };
    }
}