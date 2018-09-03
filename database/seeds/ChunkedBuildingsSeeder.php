<?php

use App\Services\ChunkedImporter\ChunkedImporter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class LookupEnum
{
    private $map = [];
    private $lookup = [];
    private $meta = [];
    private $addedId = 0;

    public function getItemId(string $item, array $meta = [])
    {
        if (empty($item) || $item === null) {
            return null;
        }
        if ($this->isKnown($item)) {
            return $this->lookup[$item];
        }
        return $this->addItem($item, $meta);
    }

    private function addItem(string $item, array $meta = []): int
    {
        $this->addedId++;
        $this->map[$this->addedId] = $item;
        $this->lookup[$item] = $this->addedId;
        $meta['name'] = $item;
        $this->meta[$this->addedId] = $meta;
        return $this->addedId;
    }

    private function isKnown(string $item): bool
    {
        return isset($this->lookup[$item]);
    }

    public function getCurrentMeta(): array
    {
        return $this->meta;
    }

    public function clean()
    {
        $this->map = [];
        $this->lookup = [];
        $this->meta = [];
        return $this;
    }

    public function loadFromCollection(Collection $collection)
    {
        foreach ($collection as $item) {
            $this->addItem($item['name']);
        }

        return $this;
    }

}

class ChunkedBuildingsSeeder extends Seeder
{

    /** @var LookupEnum */
    private $streets;
    /** @var LookupEnum */
    private $cityArea;
    /** @var LookupEnum */
    private $microArea;
    /** @var LookupEnum */
    private $materials;
    /** @var LookupEnum */
    private $buildings;


    /**
     * @return ChunkedImporter
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function getReader(): ChunkedImporter
    {
        return ChunkedImporter::create(database_path('seeds/sources/buildings1.xlsx'), range('A', 'L'), 2);
    }


    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function run(): void
    {
        $this->cleanTables();
        $this->prepareCaches();
        $reader = $this->getReader();
        $this->command->info('Reader ready');
        $chunkId = 0;
        $rowCount = 0;
        $sheetRows = 0;
        $curSheet = null;
        $reader->each(function (Worksheet $sheet) use (&$chunkId, &$rowCount, &$sheetRows, &$curSheet, $reader) {
            $sheet->garbageCollect();
            if ($sheet->getTitle() !== $curSheet) {
                $curSheet = $sheet->getTitle();
                $this->command->info('Processing sheet ' . $sheet->getTitle());
                $this->command->warn('Previous sheet : ' . $sheetRows);
                $sheetRows = 0;
            }
            $this->command->info('ChunkId : ' . $chunkId++);
            $data = $sheet->toArray(null, false, false, false);
            $dataSize = count($data);
            foreach ($data as $key => $row) {
                try {
                    if ($this->addBuilding($row) !== null) {
                        $sheetRows++;
                    }
                } catch (Exception $e) {
                    dd($row, $key, $row, $e);
                }
            }
            $rowCount += $dataSize;
            $this->command->info('Inserted: ' . $sheetRows);
            $this->command->info('RowCount: ' . $rowCount);
            $this->command->error(round(memory_get_usage() / 1024 / 1204, 2) . ' Mb');
            gc_collect_cycles();
        });

        $this->saveLookups();
        $this->command->info('***** OK *****');
    }

    public function cleanTables()
    {
        \DB::table('streets')->delete();
        \DB::table('buildings')->delete();
        \DB::table('city_micro_areas')->delete();
        \DB::table('wall_materials')->delete();
        $this->command->info('Tables cleared');
    }

    /**
     * @param LookupEnum $lookup
     * @param string $model
     */
    public function saveLookup(LookupEnum $lookup, string $model)
    {
        $values = $lookup->getCurrentMeta();
        $model::unguard();
        foreach ($values as $id => $row) {
            $model::create($row);
        }
        $model::reguard();
    }

    public function saveLookups()
    {
        $models = [
            \App\Models\WallMaterial::class => $this->materials,
            \App\Models\CityMicroArea::class => $this->microArea,
            \App\Dictionary\Street::class => $this->streets,
            \App\Models\Building::class => $this->buildings
        ];

        foreach ($models as $model => $lookup) {
            $this->command->warn('Saving model ' . $model . '...');
            $this->saveLookup($lookup, $model);
            $this->command->info('Model saved!');
        }
    }

    public function prepareCaches()
    {
        $this->streets = new LookupEnum();
        $this->materials = new LookupEnum();
        $this->microArea = new LookupEnum();
        $this->buildings = new LookupEnum();
        $this->cityArea = new LookupEnum();
        $this->cityArea->loadFromCollection(\App\Dictionary\CityArea::all());
        $this->command->info('Lookup objects created');
    }

    public function addBuilding(array $row)
    {
        if ($this->isAllNull($row)) {
            //$this->command->warn('Skipped empty or header rows');
            return null;
        }
        $mapping = [
            0 => 'city_area_id',
            2 => 'building_number',
            3 => 'street_id',
            4 => 'city_micro_area_id',
            5 => 'object_type_id',
            6 => 'year_of_development',
            7 => 'number_of_storeys',
            8 => 'square',
            9 => 'square_total',
            10 => 'wall_material_id',
            11 => 'features',
        ];

        $meta = [
        ];
        foreach ($mapping as $index => $column) {
            $value = $row[$index] ?? null;
            $meta[$column] = $this->filter($value);
        }

        $meta['name'] = implode(' ', [$meta['city_micro_area_id'], $meta['street_id'], $meta['building_number']]);
        $meta['square'] = $this->formatNumeric($meta['square']);
        $meta['square_total'] = $this->formatNumeric($meta['square_total']);
        $meta['number_of_storeys'] = $this->formatNumeric($meta['number_of_storeys']);
        $cityAreaId = $this->addCityArea($meta['city_area_id']);
        $microAreaId = $this->addMicroArea($meta['city_micro_area_id'], ['city_area_id' => $cityAreaId]);
        $streetId = $this->addStreet($meta['street_id'], ['city_area_id' => $cityAreaId, 'city_micro_area_id' => $microAreaId]);
        $meta['city_area_id'] = $cityAreaId;
        $meta['city_micro_area_id'] = $microAreaId;
        $meta['street_id'] = $streetId;
        if (is_array($meta['wall_material_id'])) {
            dd($meta, $meta['wall_material_id']);
        }
        $meta['wall_material_id'] = $this->addWallMaterial($meta['wall_material_id']);

        return $this->buildings->getItemId($meta['name'], $meta);
    }

    public function addStreet(string $name, array $meta)
    {
        return $this->streets->getItemId($name, $meta);
    }

    public function addWallMaterial(string $material)
    {
        return $this->materials->getItemId($material);
    }

    public function addMicroArea(string $name, array $meta)
    {
        return $this->microArea->getItemId($name, $meta);
    }

    private function addCityArea(string $name)
    {
        return $this->cityArea->getItemId($name);
    }

    public function formatNumeric(string $value)
    {
        if ($value === null) {
            return null;
        }
        $value = str_replace(',', '.', $value);
        return $this->emptyAsNull($value);
    }

    public function emptyAsNull(string $value)
    {
        return empty($value) ? null : $value;
    }

    public function matchesAsNull(string $value)
    {
        $null = [
            '<Null>',
            '<NULL>',
            'не уст.'
        ];

        return in_array($value, $null, true) ? null : $value;
    }

    public function filter($value)
    {
        return $this->matchesAsNull(trim($value));
    }

    public function isAllNull(array $row)
    {
        foreach ($row as $value) {
            if ($value !== null) {
                return false;
            }
        }
        return true;
    }
}
