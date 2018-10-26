<?php

use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    use \App\Services\Importer\Importer\CommonImporterTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('staff')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');*/

        $raw_data = $this->parseItems(database_path('seeds/sources/staff.xlsx'));
        unset($raw_data[0]);
        foreach ($raw_data as $raw_datum) {
            if(str_replace(' ', '', $raw_datum[1])){
                $datum = [
                    'department_id' => trim($raw_datum[0]),
                    'name' => trim($raw_datum[1]),
                    'date_birth' => trim($raw_datum[2]),
                    'rank' => trim($raw_datum[3]),
                ];

                $fireDep = \App\FireDepartment::title($datum['department_id'])->first();
                if($fireDep){
                    $datum['department_id'] = $fireDep->id;
                    $staff = \App\Models\Staff::firstOrCreate($datum);
                }
            }
        }
    }
}
