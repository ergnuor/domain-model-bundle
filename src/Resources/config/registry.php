<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {

    $container->services()
        ->set('ergnuor.domain_model.registry', \Ergnuor\DomainModel\Registry::class)
            ->args([
                service('ergnuor.domain_model.entity_manager'),
                service('ergnuor.domain_model.serializer'),
                service('ergnuor.criteria.config_builder'),
                tagged_locator('ergnuor.criteria.field_expression_mapper'),
            ])

        ->alias(\Ergnuor\DomainModel\RegistryInterface::class, 'ergnuor.domain_model.registry')

    ;
};
