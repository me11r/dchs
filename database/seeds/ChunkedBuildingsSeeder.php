<?php

use App\Services\ChunkedImporter\ChunkedImporter;
use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 03.09.2018
 * Time: 16:47
 */

class ChunkedBuildingsSeeder extends Seeder
{
    /**
     * @return ChunkedImporter
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function getReader(): ChunkedImporter
    {
        return App\Services\ChunkedImporter\ChunkedImporter::create(database_path('seeds/sources/buildings1.xlsx'), range('A', 'K'), 1);
    }


    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function run(): void
    {
        $reader = $this->getReader();
        $reader->eachSheet(function ($sheet){
            dd($sheet);
        });
    }
}
