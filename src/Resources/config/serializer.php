<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ergnuor\DomainModel\Serializer\JsonSerializer;
use Ergnuor\DomainModel\Serializer\Normalizer\BaseObjectNormalizer;
use Ergnuor\DomainModel\Serializer\Normalizer\CollectionNormalizer;
use Ergnuor\DomainModel\Serializer\Normalizer\DateTimeNormalizer;
use Ergnuor\DomainModel\Serializer\Normalizer\DoctrineEntityObjectNormalizer;
use Ergnuor\DomainModel\Serializer\Normalizer\DoctrineEntityObjectNormalizer\DoctrineEntityClassMetadataGetter;
use Ergnuor\DomainModel\Serializer\Normalizer\DomainEntityNormalizer;

return static function (ContainerConfigurator $container) {

    $container->services()
        ->set('ergnuor.domain_model.serializer.domain_entity_serializer', JsonSerializer::class)
            ->args([[], [service('serializer.encoder.json')]])

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto_serializer', JsonSerializer::class)
            ->args([[], [service('serializer.encoder.json')]])

        // common normalizers

        ->set('ergnuor.domain_model.serializer.common.normalizer.base_object_normalizer', BaseObjectNormalizer::class)
            ->parent('serializer.normalizer.object')

        ->set('ergnuor.domain_model.serializer.common.denormalizer.unwrapping')
            ->parent('serializer.denormalizer.unwrapping')

        ->set('ergnuor.domain_model.serializer.common.normalizer.collection', CollectionNormalizer::class)

        ->set('ergnuor.domain_model.serializer.common.normalizer.domain_entity', DomainEntityNormalizer::class)
            ->args([
                service('serializer.name_converter.metadata_aware'),
                service('serializer.property_accessor'),
                service('property_info')->ignoreOnInvalid(),
                service('serializer.mapping.class_discriminator_resolver')->ignoreOnInvalid(),
            ])
            ->call('setDomainEntityManager', [service('ergnuor.domain_model.entity_manager')])

        ->set('ergnuor.domain_model.serializer.common.normalizer.doctrine_entity.class_metadata_getter', DoctrineEntityClassMetadataGetter::class)

        ->set('ergnuor.domain_model.serializer.common.normalizer.doctrine_entity', DoctrineEntityObjectNormalizer::class)
            ->parent('ergnuor.domain_model.serializer.common.normalizer.base_object_normalizer')
            ->args([
                service('ergnuor.domain_model.serializer.common.normalizer.doctrine_entity.class_metadata_getter')
            ])

        ->set('ergnuor.domain_model.serializer.common.normalizer.backed_enum')
            ->parent('serializer.normalizer.backed_enum')

        ->set('ergnuor.domain_model.serializer.common.normalizer.json_serializable')
            ->parent('serializer.normalizer.json_serializable')

        ->set('ergnuor.domain_model.serializer.common.normalizer.datetime', DateTimeNormalizer::class)
            ->parent('serializer.normalizer.datetime')

        ->set('ergnuor.domain_model.serializer.common.normalizer.datetimezone')
            ->parent('serializer.normalizer.datetimezone')

        ->set('ergnuor.domain_model.serializer.common.normalizer.dateinterval')
            ->parent('serializer.normalizer.dateinterval')

        ->set('ergnuor.domain_model.serializer.common.normalizer.data_uri')
            ->parent('serializer.normalizer.data_uri')

        ->set('ergnuor.domain_model.serializer.common.denormalizer.array')
            ->parent('serializer.denormalizer.array')

        ->set('ergnuor.domain_model.serializer.common.normalizer.object')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.base_object_normalizer')


        // domain entity normalizers

        ->set('ergnuor.domain_model.serializer.domain_entity.denormalizer.unwrapping')
            ->parent('ergnuor.domain_model.serializer.common.denormalizer.unwrapping')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 0])

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.collection')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.collection')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 200])

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.domain_entity')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.domain_entity')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 400])

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.doctrine_entity.class_metadata_getter')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.doctrine_entity.class_metadata_getter')

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.doctrine_entity')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.doctrine_entity')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 600])
            ->arg(7, service('ergnuor.domain_model.serializer.domain_entity.normalizer.doctrine_entity.class_metadata_getter'))

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.backed_enum')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.backed_enum')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 800])

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.json_serializable')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.json_serializable')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 1000])

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.datetime')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.datetime')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 1200])

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.datetimezone')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.datetimezone')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 1400])

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.dateinterval')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.dateinterval')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 1600])

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.data_uri')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.data_uri')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 1800])

        ->set('ergnuor.domain_model.serializer.domain_entity.denormalizer.array')
            ->parent('ergnuor.domain_model.serializer.common.denormalizer.array')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 2000])

        ->set('ergnuor.domain_model.serializer.domain_entity.normalizer.object')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.object')
            ->tag('ergnuor.domain_model.serializer.domain_entity', ['priority' => 2200])


        // table data gateway normalizers

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.denormalizer.unwrapping')
            ->parent('ergnuor.domain_model.serializer.common.denormalizer.unwrapping')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 0])

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.collection')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.collection')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 200])

//        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.domain_entity')
//            ->parent('ergnuor.domain_model.serializer.common.normalizer.domain_entity')
//            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 400])

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.doctrine_entity.class_metadata_getter')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.doctrine_entity.class_metadata_getter')

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.doctrine_entity')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.doctrine_entity')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 600])
            ->arg(7, service('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.doctrine_entity.class_metadata_getter'))

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.backed_enum')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.backed_enum')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 800])

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.json_serializable')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.json_serializable')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 1000])

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.datetime')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.datetime')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 1200])

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.datetimezone')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.datetimezone')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 1400])

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.dateinterval')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.dateinterval')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 1600])

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.data_uri')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.data_uri')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 1800])

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.denormalizer.array')
            ->parent('ergnuor.domain_model.serializer.common.denormalizer.array')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 2000])

        ->set('ergnuor.domain_model.serializer.table_data_gateway_dto.normalizer.object')
            ->parent('ergnuor.domain_model.serializer.common.normalizer.object')
            ->tag('ergnuor.domain_model.serializer.table_data_gateway_dto', ['priority' => 2200])

    ;
};
