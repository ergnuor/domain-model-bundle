<?php

declare(strict_types=1);

namespace Ergnuor\DomainModelBundle\CacheWarmer;

use Ergnuor\DomainModel\EntityManager\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\AbstractPhpFileCacheWarmer;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class DomainModelMetadataCacheWarmer extends AbstractPhpFileCacheWarmer
{
    private string $phpArrayFile;
    private EntityManagerInterface $domainEntityManager;

    public function __construct(
        EntityManagerInterface $domainEntityManager,
        string $phpArrayFile
    ) {
        $this->domainEntityManager = $domainEntityManager;
        $this->phpArrayFile = $phpArrayFile;

        parent::__construct($phpArrayFile);
    }

    public function isOptional(): bool
    {
        return false;
    }

    /** @param string $cacheDir */
    protected function doWarmUp($cacheDir, ArrayAdapter $arrayAdapter): bool
    {
        if (\is_file($this->phpArrayFile)) {
            return false;
        }

        $metadataFactory = $this->domainEntityManager->getClassMetadataFactory();
        if (count($metadataFactory->getLoadedMetadata()) > 0) {
            $class = __CLASS__;
            throw new \LogicException("{$class} must load metadata first, check priority of your warmers.");
        }

        $metadataFactory->setCache($arrayAdapter);
        $metadataFactory->getAllMetadata();

        return true;
    }
}
