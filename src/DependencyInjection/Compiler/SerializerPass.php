<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\DependencyInjection\Compiler;

use Ergnuor\DomainModel\Serializer\Normalizer\DoctrineEntityObjectNormalizer\DoctrineEntityClassMetadataGetter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Reference;

class SerializerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;
    use DoctrineEntityManagerListDependencyTrait;

    public function process(ContainerBuilder $container)
    {
        $this->configureDoctrineEntityNormalizer($container);

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

    private function configureDoctrineEntityNormalizer(ContainerBuilder $container): void
    {
        $domainEntityDoctrineNormalizer = $container->getDefinition('ergnuor.domain_model.serializer.domain_entity.normalizer.doctrine_entity');
        $domainEntityDoctrineNormalizer->replaceArgument(
            7,
            new Reference('ergnuor.domain_model.serializer.domain_entity.normalizer.doctrine_entity.class_metadata_getter')
        );

        $tableDataGatewayDoctrineNormalizer = $container->getDefinition('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.doctrine_entity');
        $tableDataGatewayDoctrineNormalizer->replaceArgument(
            7,
            new Reference('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.doctrine_entity.class_metadata_getter')
        );

        if (!$container->hasParameter('doctrine.entity_managers')) {
            $domainEntityDoctrineNormalizer
                ->clearTag('ergnuor.domain_model.serializer.domain_entity');
            $tableDataGatewayDoctrineNormalizer
                ->clearTag('ergnuor.domain_model.serializer.domain_entity');

            return;
        }

        $this->setDoctrineEntityManagersListDependency(
            $container,
            'ergnuor.domain_model.serializer.common.normalizer.doctrine_entity.doctrine_entity_class_metadata_getter',
            0,
            DoctrineEntityClassMetadataGetter::class,
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
