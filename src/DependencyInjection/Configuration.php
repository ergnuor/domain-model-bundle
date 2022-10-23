<?php
declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ergnuor_domain_model');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('entity_paths')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->scalarPrototype()
                        ->cannotBeEmpty()
                    ->end()
                    ->beforeNormalization()->castToArray()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}