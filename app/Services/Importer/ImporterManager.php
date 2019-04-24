<?php

namespace App\Services\Importer;

use App\Models\Hydrant;
use App\Models\SpecialPlan;
use App\Services\Importer\Importer\HydrantImporter;
use App\Services\Importer\Importer\ImporterInterface;
use App\Services\Importer\Importer\SpecialPlanImporter;
use App\Services\Importer\Importer\Ticket101OtherImporter;
use App\Ticket101Other;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ImporterManager
{
    /**
     * @var ImporterFactory
     */
    protected $importerFactory;

    /**
     * ImporterManager constructor.
     * @param ImporterFactory $importerFactory
     */
    public function __construct(ImporterFactory $importerFactory)
    {
        $this->importerFactory = $importerFactory;
    }

    /**
     * @param string $filePath
     * @return ImporterInterface
     */
    public function specialPlanImportFile(string $filePath): ImporterInterface
    {
        return $this->importFile($filePath, SpecialPlanImporter::class, SpecialPlan::class);
    }

    /**
     * @param string $filePath
     * @return ImporterInterface
     */
    public function hydrantImportFile(string $filePath): ImporterInterface
    {
        return $this->importFile($filePath, HydrantImporter::class, Hydrant::class);
    }

    public function ticket101OtherImportFile(string $filePath, string $Class): ImporterInterface
    {
        return $importer = $this
            ->importerFactory
            ->createImporter($Class)
            ->loadFile($filePath);
    }

    /**
     * @param string $filePath
     * @param string $Class
     * @param string $modelClass
     * @return ImporterInterface
     */
    private function importFile(string $filePath, string $Class, string $modelClass): ImporterInterface
    {
        $importer = $this
            ->importerFactory
            ->createImporter($Class)
            ->loadFile($filePath);

        foreach ($importer->getItems() as $item) {
            /**@var Model | Builder $model */
            $model = new $modelClass;
            $model->firstOrCreate($item);
        }

        return $importer;
    }

}
