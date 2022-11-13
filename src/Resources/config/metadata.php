<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ergnuor\Mapping\ClassMetadataFactory;
use Ergnuor\DomainModel\Mapping\ClassMetadataFactoryAdapter;

return static function (ContainerConfigurator $container) {

    $container->services()
        ->set('ergnuor.domain_model.mapping.class_metadata_factory_adapter', ClassMetadataFactoryAdapter::class)
            ->args([
                []
            ])

        ->set('ergnuor.domain_model.mapping.class_metadata_factory', ClassMetadataFactory::class)
            ->args([
                service('ergnuor.domain_model.mapping.class_metadata_factory_adapter')
            ])
            ->call('setCache', [service('ergnuor.domain_model.mapping.class_metadata_cache')])
    ;
};
