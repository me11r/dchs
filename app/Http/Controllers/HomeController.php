<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Services\Importer\Importer\CommonImporterTrait;
use Illuminate\Http\Request;

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
        $raw_data = $this->parseItems(database_path('seeds/sources/vehicles.xlsx'));

        $raw_data_less = [];

//        $data_to_save = [];

        foreach ($raw_data as $raw_datum) {
            $temp_item = array_slice($raw_datum, 0, 9);
            $changed_keys['fire_department_id'] = $temp_item[0];
            $changed_keys['vehicle_type_id'] = $temp_item[1];
            $changed_keys['name'] = trim($temp_item[2]);
            $changed_keys['base'] = $temp_item[3];
            $changed_keys['publish_year'] = $temp_item[4];
            $changed_keys['number_old'] = $temp_item[5] == '-' ? null : trim($temp_item[5]);
            $changed_keys['number'] = $temp_item[6];
            $changed_keys['reg_certificate'] = $temp_item[7];
            $changed_keys['note'] = $temp_item[8];
            $raw_data_less[] = $changed_keys;
        }

        foreach ($raw_data_less as $key => $item) {
            $fire_dep_id = FireDepartment::title($item['fire_department_id'])->first();
            $vehicle_types = [
                'Основ',
                'Спец',
                'Вспом',
            ];

            if($fire_dep_id){
                $raw_data_less[$key]['fire_department_id'] = $fire_dep_id->id;

                if(str_contains($item['vehicle_type_id'], $vehicle_types[0])){
                    $vehicle_type = VehicleType::where('name', 'like', "$vehicle_types[0]%")->first();
                }
                elseif(str_contains($item['vehicle_type_id'], $vehicle_types[1])){
                    $vehicle_type = VehicleType::where('name', 'like', "$vehicle_types[1]%")->first();
                }
                else{
                    $vehicle_type = VehicleType::where('name', 'like', "Вспом%")->first();
                }

                if($vehicle_type){
                    $raw_data_less[$key]['vehicle_type_id'] = $vehicle_type->id;
                }
            }
            else{
                unset($raw_data_less[$key]);
            }


        }
        $f = 1;

        return $raw_data_less;
    }
}
