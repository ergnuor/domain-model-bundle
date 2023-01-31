<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\DependencyInjection;

use Ergnuor\DomainModel\Criteria\FieldMapper\FieldExpressionMapperInterface;
use Ergnuor\DomainModel\Persister\AggregateRootPersisterInterface;
use Ergnuor\DomainModel\Persister\EntityPersisterInterface;
use Ergnuor\DomainModel\Repository\DomainRepositoryInterface;
use Ergnuor\DomainModelBundle\CacheWarmer\DomainModelMetadataCacheWarmer;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class ErgnuorDomainModelExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->setContainerParameters($container, $config);

        $loader = new PhpFileLoader(
            $container,
            new FileLocator(\dirname(__DIR__) . '/Resources/config')
        );

        $loader->load('common.php');
        $loader->load('serializer.php');
        $loader->load('entity_manager.php');

        $loader->load('metadata.php');
        $this->defineMetadataServices($container);

        $container->registerForAutoconfiguration(AggregateRootPersisterInterface::class)
            ->addTag('ergnuor.domain_model.persister');
        $container->registerForAutoconfiguration(EntityPersisterInterface::class)
            ->addTag('ergnuor.domain_model.persister');

        $container->registerForAutoconfiguration(DomainRepositoryInterface::class)
            ->addTag('ergnuor.domain_model.repository');

        $container->registerForAutoconfiguration(FieldExpressionMapperInterface::class)
            ->addTag('ergnuor.domain_model.criteria.expression_mapper_service');
    }

    private function defineMetadataServices(ContainerBuilder $container): void
    {
        $cacheId = 'ergnuor.domain_model.mapping.class_metadata_cache';

        $cache = new Definition(ArrayAdapter::class);

        if (!$container->getParameter('kernel.debug')) {
            $phpArrayFile = '%kernel.cache_dir%/ergnuor/domain_model/metadata.php';

            $container->register('ergnuor.domain_model.mapping.class_metadata_cache_warmer', DomainModelMetadataCacheWarmer::class)
                ->setArguments([new Reference('ergnuor.domain_model.entity_manager'), $phpArrayFile])
                ->addTag('kernel.cache_warmer', ['priority' => 1000]);

            $cache = new Definition(PhpArrayAdapter::class, [$phpArrayFile, $cache]);
        }

        $container->setDefinition($cacheId, $cache);
    }

    private function setContainerParameters(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('ergnuor.domain_model.entity_paths', $config['entity_paths']);
    }
}
