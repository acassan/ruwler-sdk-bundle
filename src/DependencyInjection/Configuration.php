<?php

namespace Ruwler\SdkBundle\DependencyInjection;

use Ruwler\SdkBundle\RuwlerService;
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
                ->scalarNode('client')
                    ->defaultValue(RuwlerService::class)
                ->end()
                ->scalarNode('api_key')
                    ->beforeNormalization()
                        ->ifString()
                        ->then($this->getTrimClosure())
                    ->end()
                    ->defaultNull()
                ->end();
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