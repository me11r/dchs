<?php

namespace App\Services\Importer;

use App\Services\Importer\Importer\ImporterInterface;
use Illuminate\Contracts\Container\Container;

class ImporterFactory
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * SiteMapGeneratorFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $className
     * @return ImporterInterface
     */
    public function createImporter(string $className): ImporterInterface
    {
        return $this->container->make($className);
    }

}
