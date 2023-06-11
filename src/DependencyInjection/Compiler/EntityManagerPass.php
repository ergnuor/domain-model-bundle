<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\DependencyInjection\Compiler;

use Ergnuor\DomainModel\Transaction\DoctrineTransactionManager;
use Ergnuor\SerializerBundle\DependencyInjection\Compiler\DoctrineEntityManagerListDependencyTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EntityManagerPass implements CompilerPassInterface
{
    use DoctrineEntityManagerListDependencyTrait;

    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('ergnuor.domain_model.unit_of_work.transaction_manager')) {
            return;
        }

        if (!$container->hasParameter('doctrine.entity_managers')) {
            $container->log(
                $this,
                sprintf(
                    'Can not configure "%s" service. You possibly want to override "%s" service or install doctrine/orm package',
                    'ergnuor.domain_model.unit_of_work.transaction_manager',
                    'ergnuor.domain_model.unit_of_work.transaction_manager'
                )
            );

            return;
        }

        $transactionManagerId = 'ergnuor.domain_model.unit_of_work.transaction_manager';
        $transactionManagerClass = DoctrineTransactionManager::class;

        $container->register($transactionManagerId, $transactionManagerClass)
            ->setArguments([[]]);

        $this->setDoctrineEntityManagersListDependency(
            $container,
            $transactionManagerId,
            0,
            $transactionManagerClass,
        );
    }
}
