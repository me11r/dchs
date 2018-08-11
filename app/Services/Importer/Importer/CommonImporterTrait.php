<?php

namespace App\Services\Importer\Importer;

use PhpOffice\PhpSpreadsheet\IOFactory;

trait CommonImporterTrait
{
    /**
     * @param $filePath
     * @param $readerType
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function parseItems($filePath, $readerType = 'Xlsx'): array
    {
        $reader = IOFactory::createReader($readerType);
        $spreadsheet = $reader->load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        return $worksheet->toArray();
    }
}
