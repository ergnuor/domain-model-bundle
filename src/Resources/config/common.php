<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ergnuor\DomainModel\Criteria\ConfigBuilder\ConfigBuilder;

return static function (ContainerConfigurator $container) {

    $container->services()
        ->set('ergnuor.domain_model.criteria.config_builder', ConfigBuilder::class)

    ;
};
