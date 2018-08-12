<?php

namespace App\Services\Importer\Importer;


interface ImporterInterface
{

    /**
     * @param $filePath
     * @return ImporterInterface
     */
    public function loadFile($filePath): ImporterInterface;

    /**
     * @return array[]
     */
    public function getItems(): array;

    /**
     * @return array[]
     */
    public function getIncorrectItems(): array ;
}
