<?php

use Illuminate\Database\Seeder;

class IncidentTypeCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Техногенный'],
            ['name' => 'Природный'],
        ];

        foreach ($types as $type){
            \App\IncidentTypeCategory::firstOrCreate($type);
        }

        \App\Models\IncidentType::where('id', '>', 0)->update(['category_id' => 1]);
    }
}
