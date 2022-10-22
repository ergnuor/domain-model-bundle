<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

class SerializerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container)
    {
        $this->setNormalizers(
            $container,
            'ergnuor.domain_model.serializer.domain_entity_serializer',
            'ergnuor.domain_model.serializer.domain_entity'
        );

        $this->setNormalizers(
            $container,
            'ergnuor.domain_model.serializer.table_data_gateway_dto_serializer',
            'ergnuor.domain_model.serializer.table_data_gateway_dto'
        );
    }

    private function setNormalizers(
        ContainerBuilder $container,
        string $serializerServiceId,
        string $normalizersTag
    ): void {
        $normalizers = $this->findAndSortTaggedServices($normalizersTag, $container);

        if (!$normalizers) {
            throw new RuntimeException(
                sprintf(
                    'You must tag at least one service as "%s" to use the "%s" service.',
                    $normalizersTag,
                    $serializerServiceId
                )
            );
        }

        $serializerDefinition = $container->getDefinition($serializerServiceId);
        $serializerDefinition->replaceArgument(0, $normalizers);
    }
}
