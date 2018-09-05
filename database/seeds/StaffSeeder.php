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
        $raw_data = $this->parseItems(database_path('seeds/sources/staff.xlsx'));
        unset($raw_data[0]);
        foreach ($raw_data as $raw_datum) {
            $datum = [
                'department_id' => trim($raw_datum[0]),
                'name' => trim($raw_datum[1]),
                'date_birth' => trim($raw_datum[2]),
                'rank' => trim($raw_datum[3]),
            ];

            $fireDep = \App\FireDepartment::title($datum['department_id'])->firstOrFail();
            if($fireDep){
                $datum['department_id'] = $fireDep->id;
                $staff = \App\Models\Staff::firstOrCreate($datum);
            }
        }
    }
}
