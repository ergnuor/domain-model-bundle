<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MetadataPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $classMetadataFactoryAdapterDefinition = $container->getDefinition('ergnuor.domain_model.mapping.class_metadata_factory_adapter');
        $classMetadataFactoryAdapterDefinition->replaceArgument(
            0,
            $container->getParameter('ergnuor.domain_model.entity_paths')
        );
    }
}
