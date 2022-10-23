<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ergnuor\DomainModel\EntityManager\EntityManager;
use Ergnuor\DomainModel\EntityManager\EntityManagerInterface;
use Ergnuor\DomainModel\EntityManager\UnitOfWork;
use Ergnuor\DomainModel\RegistryInterface;
use Ergnuor\DomainModel\Transaction\DoctrineTransactionManager;

return static function (ContainerConfigurator $container) {

    $container->services()
        ->set('ergnuor.domain_model.unit_of_work.transaction_manager', DoctrineTransactionManager::class)
            ->args([[]])

        ->set('ergnuor.domain_model.unit_of_work', UnitOfWork::class)
            ->args([
                tagged_locator('ergnuor.domain_model.persister'),
                service('ergnuor.domain_model.serializer.domain_entity_serializer'),
                service('ergnuor.domain_model.unit_of_work.transaction_manager'),
                service('event_dispatcher')
            ])

        ->set('ergnuor.domain_model.entity_manager', EntityManager::class)
            ->args([
                tagged_locator('ergnuor.domain_model.repository'),
                service('ergnuor.domain_model.unit_of_work'),
                service('ergnuor.domain_model.mapping.class_metadata_factory')
            ])

        ->alias(EntityManagerInterface::class, 'ergnuor.domain_model.entity_manager')

        ->set('ergnuor.domain_model.registry', \Ergnuor\DomainModel\Registry::class)
            ->args([
                service('ergnuor.domain_model.entity_manager'),
                service('ergnuor.domain_model.serializer.domain_entity_serializer'),
                service('ergnuor.domain_model.serializer.table_data_gateway_dto_serializer'),
            ])

        ->alias(RegistryInterface::class, 'ergnuor.domain_model.registry')

    ;
};
