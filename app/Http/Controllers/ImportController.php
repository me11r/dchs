<?php

namespace App\Http\Controllers;

use App\Services\Importer\Importer\Ticket101OtherImporter;
use App\Services\Importer\ImporterManager;
use App\Ticket101Other;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ImportController extends AuthorizedController
{
    private $importerManager;

    public function __construct(ImporterManager $importerManager)
    {
        parent::__construct();

        $this->importerManager = $importerManager;
    }

    public function index(): void
    {
    }

    public function specialPlans(Request $request)
    {
        $data = [
            'importName' => 'Оперативные планы',
        ];

        $file = $request->file('file');
        if ($file) {
            $importer = $this->importerManager->specialPlanImportFile($file->getRealPath());
            $data['incorrectItems'] = $importer->getIncorrectItems();
            $data['importedItems'] = $importer->getItems();
        }

        return View::make('import.import_results', $data);
    }

    public function hydrants(Request $request)
    {
        $data = [
            'importName' => 'Гидранты',
        ];

        $file = $request->file('file');
        if ($file) {
            $importer = $this->importerManager->hydrantImportFile($file->getRealPath());
            $data['incorrectItems'] = $importer->getIncorrectItems();
            $data['importedItems'] = $importer->getItems();
        }

        return View::make('import.import_results', $data);
    }

    public function cards101Other(Request $request)
    {
        $data = [
            'importName' => 'Карточки 101: прочие',
            'delete_path' => 'card101-other',
        ];

        $file = $request->file('file');
        if ($file) {
            $importer = $this->importerManager->ticket101OtherImportFile($file->getRealPath(), Ticket101OtherImporter::class);
            $data['incorrectItems'] = $importer->getIncorrectItems();
            $data['importedItems'] = $importer->getItems();
        }

        return View::make('import.import_results', $data);
    }

    public function destroy($model)
    {
        if($model === 'card101-other') {
            Ticket101Other::imported()->delete();
        }

        return redirect('import');
    }



}
