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
        return IOFactory::createReader($readerType)->load($filePath)->getActiveSheet()->toArray();
    }
}
