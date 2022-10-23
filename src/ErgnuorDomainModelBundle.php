<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle;

use Ergnuor\DomainModelBundle\DependencyInjection\Compiler\EntityManagerPass;
use Ergnuor\DomainModelBundle\DependencyInjection\Compiler\MetadataPass;
use Ergnuor\DomainModelBundle\DependencyInjection\Compiler\SerializerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ErgnuorDomainModelBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SerializerPass());
        $container->addCompilerPass(new EntityManagerPass());
        $container->addCompilerPass(new MetadataPass());
    }

}