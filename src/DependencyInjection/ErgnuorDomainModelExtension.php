<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\DependencyInjection;

use Ergnuor\DomainModel\Persister\AggregateRootPersisterInterface;
use Ergnuor\DomainModel\Persister\EntityPersisterInterface;
use Ergnuor\DomainModel\Repository\DomainRepositoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class ErgnuorDomainModelExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(\dirname(__DIR__) . '/Resources/config')
        );

        $loader->load('serializer.php');
        $loader->load('entity_manager.php');

        $container->registerForAutoconfiguration(AggregateRootPersisterInterface::class)
            ->addTag('ergnuor.domain_model.persister');
        $container->registerForAutoconfiguration(EntityPersisterInterface::class)
            ->addTag('ergnuor.domain_model.persister');

        $container->registerForAutoconfiguration(DomainRepositoryInterface::class)
            ->addTag('ergnuor.domain_model.repository');

    }
}
