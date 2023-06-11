<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ergnuor\DomainModel\Serializer\Normalizer\DomainEntityNormalizer;
use Symfony\Component\Serializer\Serializer;

return static function (ContainerConfigurator $container) {

    $container->services()
        ->set('ergnuor.domain_model.serializer', Serializer::class)
            ->args([[], [service('serializer.encoder.json')]])

        // normalizers

        ->set('ergnuor.domain_model.serializer.denormalizer.unwrapping')
            ->parent('serializer.denormalizer.unwrapping')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 2200])

        ->set('ergnuor.domain_model.serializer.normalizer.collection')
            ->parent('ergnuor.serializer.normalizer.collection')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 2000])

        ->set('ergnuor.domain_model.serializer.normalizer.domain_entity', DomainEntityNormalizer::class)
            ->args([
                service('ergnuor.domain_model.mapping.class_metadata_factory'),
                service('serializer.name_converter.metadata_aware'),
                service('serializer.property_accessor'),
                service('property_info')->ignoreOnInvalid(),
                service('serializer.mapping.class_discriminator_resolver')->ignoreOnInvalid(),
                null,
            ])
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 1800])

        ->set('ergnuor.domain_model.serializer.normalizer.doctrine_entity')
            ->parent('ergnuor.serializer.normalizer.doctrine_entity')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 1600])

        ->set('ergnuor.domain_model.serializer.normalizer.backed_enum')
            ->parent('serializer.normalizer.backed_enum')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 1400])

        ->set('ergnuor.domain_model.serializer.normalizer.json_serializable')
            ->parent('serializer.normalizer.json_serializable')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 1200])

        ->set('ergnuor.domain_model.serializer.normalizer.datetime')
            ->parent('ergnuor.serializer.normalizer.datetime')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 1000])

        ->set('ergnuor.domain_model.serializer.normalizer.datetimezone')
            ->parent('serializer.normalizer.datetimezone')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 800])

        ->set('ergnuor.domain_model.serializer.normalizer.dateinterval')
            ->parent('serializer.normalizer.dateinterval')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 600])

        ->set('ergnuor.domain_model.serializer.normalizer.data_uri')
            ->parent('serializer.normalizer.data_uri')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 400])

        ->set('ergnuor.domain_model.serializer.denormalizer.array')
            ->parent('serializer.denormalizer.array')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 200])

        ->set('ergnuor.domain_model.serializer.normalizer.object')
            ->parent('ergnuor.serializer.normalizer.object')
            ->tag('ergnuor.domain_model.serializer.normalizer', ['priority' => 0])

    ;
};
