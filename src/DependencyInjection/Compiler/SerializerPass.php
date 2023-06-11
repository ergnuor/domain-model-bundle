<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\DependencyInjection\Compiler;

use Ergnuor\SerializerBundle\DependencyInjection\Compiler\SerializerTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SerializerPass implements CompilerPassInterface
{
    use SerializerTrait;

    public function process(ContainerBuilder $container)
    {
        $this->configureDoctrineEntityNormalizer($container);

        $this->setNormalizers(
            $container,
            'ergnuor.domain_model.serializer',
            'ergnuor.domain_model.serializer.normalizer'
        );
    }

    private function configureDoctrineEntityNormalizer(ContainerBuilder $container): void
    {
        if ($container->hasParameter('doctrine.entity_managers')) {
            return;
        }

        $doctrineEntityNormalizer = $container->getDefinition('ergnuor.domain_model.serializer.normalizer.doctrine_entity');
        $doctrineEntityNormalizer->clearTag('ergnuor.domain_model.serializer.normalizer');
    }
}
