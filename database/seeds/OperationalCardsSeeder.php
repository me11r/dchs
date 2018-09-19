<?php

use Illuminate\Database\Seeder;

class OperationalCardsSeeder extends Seeder
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
        \DB::table('operational_cards')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $raw_data = $this->parseItems(database_path('seeds/sources/operational_cards.xlsx'));
        unset($raw_data[0]);
        foreach ($raw_data as $raw_datum) {
            if(str_replace(' ', '', $raw_datum[1])){
                $datum = [
                    'fire_department_id' => trim($raw_datum[0]),
                    'oc_number' => trim($raw_datum[1]),
                    'object_name' => trim($raw_datum[2]),
                    'fire_level_id' => trim($raw_datum[3]),
                    'location' => trim($raw_datum[4]),
                ];

                $fireDep = \App\FireDepartment::title($datum['fire_department_id'])->firstOrFail();
                $level = \App\Dictionary\FireLevel::name($datum['fire_level_id'])->firstOrFail();
                $datum['fire_department_id'] = $fireDep->id;
                $datum['fire_level_id'] = $level->id;

                $card = \App\OperationalCard::firstOrCreate($datum);
            }
        }
    }
}
