<?php

use Illuminate\Database\Seeder;

class SchedulesSeeder extends Seeder
{
    use \App\Services\Importer\Importer\CommonImporterTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $item) {

            \App\Models\Schedule::updateOrCreate($item, $item);
        }
    }

    public function getData()
    {
        $raw_data = $this->parseItems(database_path('seeds/sources/schedules1.xlsx'));

        $raw_data_less = [];

        \App\Models\Schedule::select('*')->delete();

        foreach ($raw_data as $raw_datum) {

            $temp_item = array_slice($raw_datum, 0, 5);

            $changed_keys['fire_department_main_id'] = trim($temp_item[1]);
            $changed_keys['fire_department_id'] = trim($temp_item[3]);
            $changed_keys['dict_fire_level_id'] = trim($temp_item[2]);
            $changed_keys['is_reserved'] = false;
            $changed_keys['department'] = $temp_item[4];

            $raw_data_less[] = $changed_keys;
        }

        foreach ($raw_data_less as $key => $item) {
            $fire_dep_main_id = \App\FireDepartment::title($item['fire_department_main_id'])->first();
            $fire_dep_id = \App\FireDepartment::title($item['fire_department_id'])->first();
            $level = \App\Dictionary\FireLevel::name($item['dict_fire_level_id'])->first();

            if($fire_dep_id && $fire_dep_main_id){
                $raw_data_less[$key]['fire_department_id'] = $fire_dep_id->id;
                $raw_data_less[$key]['fire_department_main_id'] = $fire_dep_main_id->id;
                $raw_data_less[$key]['dict_fire_level_id'] = $level->id;

            }
            else{
                unset($raw_data_less[$key]);
            }
        }

        return $raw_data_less;
    }
}
