<?php

namespace App\Http\Controllers;

use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\Street;
use App\Models\WallMaterial;
use App\Services\Importer\Importer\CommonImporterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use CommonImporterTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
//        $raw_data = $this->parseAllBooks(database_path('seeds/sources/buildings1.xlsx'));
//        $to_cache = [];
//        foreach ($raw_data as $raw_datum) {
//            $temp_array = $raw_datum->toArray();
//            $to_cache[$temp_array[1][5]] = $temp_array;
//        }
//
//        $value = Cache::forever('buildings', $to_cache);

//        $old_streets = Cache::get('old_streets');
//        $raw_data = Cache::get('buildings');
//        $raw_data_filtered = $this->changeKeys($raw_data);
        $raw_data_filtered = Cache::get('buildings_valid_keys_new');
        $defected = Cache::get('defected');

        $count = 0;
//        $f = array_map(function ($q) use (&$count){
//            $count += count($q);
//        },$raw_data_filtered);

//        \Illuminate\Support\Facades\DB::table('streets')->delete();
//        \Illuminate\Support\Facades\DB::table('buildings')->delete();
//        \Illuminate\Support\Facades\DB::table('city_micro_areas')->delete();
//        \Illuminate\Support\Facades\DB::table('wall_materials')->delete();
//
//        echo "Таблицы очищены".PHP_EOL;
//
//        if(Cache::get('buildings_valid_keys') == null){
//            $raw_data = $this->parseAllBooks(database_path('seeds/sources/buildings1.xlsx'));
//            echo "Данные Excel загружены в память".PHP_EOL;
//
//            if(Cache::get('buildings') == null){
//                $to_cache = [];
//                foreach ($raw_data as $raw_datum) {
//                    $temp_array = $raw_datum->toArray();
//                    $to_cache[$temp_array[1][5]] = $temp_array;
//                }
//
//                \Illuminate\Support\Facades\Cache::put('buildings', $to_cache, 320);
//            }
//
//            $raw_data = \Illuminate\Support\Facades\Cache::get('buildings');
//
//            $raw_data_filtered = $this->changeKeys($raw_data);
//
//            \Illuminate\Support\Facades\Cache::put('buildings_valid_keys', $raw_data_filtered, 420);
//            $raw_data_filtered = \Illuminate\Support\Facades\Cache::get('buildings_valid_keys');
//        }
//        else{
//            $raw_data_filtered = \Illuminate\Support\Facades\Cache::get('buildings_valid_keys');
//            echo "Данные из кеша загружены в память".PHP_EOL;
//        }
//
//        echo "Заполняем улицы, микрорайоны, материалы стен".PHP_EOL;
//        $res = $this->fillValues($raw_data_filtered);
//        Cache::put('buildings_valid_keys_new', $raw_data_filtered, 200);
//        echo "Заполняем строения".PHP_EOL;
//        $this->fillBuildings($raw_data_filtered);

    }

    private function fillBuildings($data)
    {
        $count = 0;
        $count1 = 0;
        array_map(function ($q) use (&$count){
            $count += count($q);
        },$data);

        foreach ($data as $datum) {
            foreach ($datum as $item) {

                \App\Models\Building::create($item);
                $now = now()->format('H:i:s');
                echo "**********{$now}**********".PHP_EOL;
                echo "Обработано: $count1 записей".PHP_EOL;
                echo "Осталось:   $count записей".PHP_EOL;
            }
        }
    }

    private function fillValues(&$inputData)
    {
        $result = [];
        $count = 0;
        $count1 = 0;
        array_map(function ($q) use (&$count){
            $count += count($q);
        },$inputData);

        foreach ($inputData as $building_type => $inputDatum) {
            foreach ($inputDatum as $key => $item) {
                $count1++;
                $count--;
                if($count1 % 1000 == 0){
                    $now = now()->format('H:i:s');
                    echo "=========={$now}============".PHP_EOL;
                    echo "Обработано: $count1 записей".PHP_EOL;
                    echo "Осталось:   $count записей".PHP_EOL;

                    return $result;
                }

                if(empty($inputData[$building_type][$key]['city_area_id'])){
                    unset($inputData[$building_type][$key]);
                    continue;
                }
                $street = \App\Dictionary\Street::createStreetIfNotExists($inputDatum[$key]['street_id'], $inputDatum[$key]['city_area_id'], $inputDatum[$key]['city_micro_area_id']);
                if(isset($street->id)){
                    $inputData[$building_type][$key]['city_area_id'] = $street->city_area_id;
                    $inputData[$building_type][$key]['street_id'] = $street->id;
                    $inputData[$building_type][$key]['city_micro_area_id'] = $street->city_micro_area_id;
                    $inputData[$building_type][$key]['object_type_id'] = $this->parseObjectType($inputDatum[$key]['object_type_id'])->id;
                    $inputData[$building_type][$key]['wall_material_id'] = $this->parseWallMaterial($inputDatum[$key]['wall_material_id'])->id;
                }
                else{
                    $result[] = $inputData[$building_type][$key];
                }
            }
        }

        return $result;
    }

    private function changeKeys($inputData)
    {
        $result = [];
        foreach ($inputData as $building_type => $inputDatum) {
            foreach ($inputDatum as $key => $item) {
                if($key != 0){
                    $inputDatum[$key] = $this->filter($inputDatum[$key]);

                    $result[$building_type][$key]['city_area_id'] = $this->filter($inputDatum[$key][0]);
                    $result[$building_type][$key]['building_number'] = $this->filter($inputDatum[$key][2]);
                    $result[$building_type][$key]['street_id'] = $this->filter($inputDatum[$key][3]) ;
                    $result[$building_type][$key]['city_micro_area_id'] = $this->filter($inputDatum[$key][4]);
                    $result[$building_type][$key]['object_type_id'] = $this->filter($inputDatum[$key][5]);
                    $result[$building_type][$key]['year_of_development'] = $this->filter($inputDatum[$key][6]);
                    $result[$building_type][$key]['number_of_storeys'] = $this->filter($inputDatum[$key][7]);
                    $result[$building_type][$key]['square'] = $this->filter($inputDatum[$key][8]);
                    $result[$building_type][$key]['square_total'] = $this->filter($inputDatum[$key][9]);
                    $result[$building_type][$key]['wall_material_id'] = $this->filter($inputDatum[$key][10]);
                    $result[$building_type][$key]['features'] = $this->filter($inputDatum[$key][11]);

                }
                else{
                    unset($inputDatum[$key]);
                }
            }
        }

        return $result;
    }

    private function filter($inputData)
    {
        if(is_array($inputData)){
            foreach ($inputData as $key => $inputDatum) {
                $filtered = $this->filter($inputDatum);
                if(str_contains($key, 'square') || in_array($key, [8,9])){
                    $filtered = str_replace(',', '.', $filtered);
                }
                $inputData[$key] = $filtered == '' ? null : $filtered;
            }
        }
        else{
            $inputData = trim($inputData);
            if($this->isNull($inputData) && !empty($inputData)){
                return null;
            }
        }

        return $inputData;

    }

    private function parseObjectType($inputData)
    {
        return \App\Dictionary\FireObject::firstOrCreate(['name' => $inputData]);
    }

    private function parseWallMaterial($inputData)
    {
        return WallMaterial::firstOrCreate(['name' => $inputData]);
    }

    private function getBurnObjectId($inputData)
    {
        $relation = [
            'дача' => 'Дачный домик',
            'гостиница' => 'Гостиницы',
            'высшее учебное заведение' => 'Учебные заведения',
            'здание учреждения культуры и искусства' => 'Культурно-массовые и зрелищные учреждения',
            'предприятие торговли' => 'Торговые предприятия',
            'предприятие общественного питания' => 'Объекты общепита',
            'сооружение системы энергоснабжения' => 'Энергетические системы',
            'административное здание' => 'Административно-общественные',
        ];

        $search = in_array($inputData, $relation) ? $relation[$inputData] : $inputData;

//        $burnObject = \App\Dictionary\BurntObject::name($search)->firstOrFail();
        $burnObject = \App\Dictionary\BurntObject::firstOrCreate(['name' => $search]);

        return $burnObject->id;
    }

    private function getCityArea($inputData)
    {
        $cityArea = CityArea::name($inputData)->firstOrFail();
        return $cityArea->id;
    }

    private function isNull($inputData)
    {
        $null = [
            '<Null>',
            'не уст.'
        ];

        return in_array($inputData, $null);
    }
}
