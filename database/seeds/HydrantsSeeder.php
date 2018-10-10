<?php

use Illuminate\Database\Seeder;

class HydrantsSeeder extends Seeder
{
    use \App\Services\Importer\Importer\CommonImporterTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('hydrants')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $raw_data = $this->parseItems(database_path('seeds/sources/hydrants.xlsx'));
        unset($raw_data[0]);
        foreach ($raw_data as $raw_datum) {
            try{
                $datum = [
                    'address' => trim($raw_datum[1]),
                    'specification' => trim($raw_datum[4]),
                    'lat' => str_replace(' ', '', trim($raw_datum[2])),
                    'long' => str_replace(' ', '', trim($raw_datum[3])),
                    'active' => trim($raw_datum[6]),
                    'fire_department_id' => trim($raw_datum[5]),
                ];

                if(substr_count($datum['lat'], '.') == 2){
                    $datum['lat'] = str_before($datum['lat'], '.').'.'.str_replace('.', '', str_after($datum['lat'], '.'));
                }

                if(substr_count($datum['long'], '.') == 2){

                    $datum['long'] = str_before($datum['long'], '.').'.'.str_replace('.', '', str_after($datum['long'], '.'));
                }

                $fireDep = \App\FireDepartment::title($datum['fire_department_id'])->first();
                if(!$fireDep){
                    $fireDep = \App\FireDepartment::title('С'.$datum['fire_department_id'])->first();
                }

                if($fireDep){
                    $datum['fire_department_id'] = $fireDep->id;
                    \App\Models\Hydrant::firstOrCreate($datum);
                }
            }
            catch (Exception $e){
                dd($datum);
                $this->command->info($e->getMessage());
            }
        }
    }
}
