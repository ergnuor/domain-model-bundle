<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\DependencyInjection\Compiler;

use Ergnuor\DomainModel\Transaction\DoctrineTransactionManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EntityManagerPass implements CompilerPassInterface
{
    use DoctrineEntityManagerListDependencyTrait;

    public function process(ContainerBuilder $container)
    {
//        if (!$container->hasParameter('doctrine.entity_managers')) {
//            $container->log(
//                $this,
//                sprintf(
//                    'Can not configure "%s" service. You possibly want to override "%s" service or install doctrine/orm package',
//                    'ergnuor.domain_model.unit_of_work.transaction_manager',
//                    'ergnuor.domain_model.unit_of_work.transaction_manager'
//                )
//            );
//        }

        $this->setDoctrineEntityManagersListDependency(
            $container,
            'ergnuor.domain_model.unit_of_work.transaction_manager',
            0,
            DoctrineTransactionManager::class,
        );
    }
}
