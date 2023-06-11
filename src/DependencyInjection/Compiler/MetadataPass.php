<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\DependencyInjection\Compiler;

use Ergnuor\DomainModelBundle\CacheWarmer\DomainModelMetadataCacheWarmer;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class MetadataPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->defineMetadataServices($container);

        $classMetadataFactoryAdapterDefinition = $container->getDefinition('ergnuor.domain_model.mapping.class_metadata_factory_adapter');
        $classMetadataFactoryAdapterDefinition->replaceArgument(
            0,
            $container->getParameter('ergnuor.domain_model.entity_paths')
        );
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
}
