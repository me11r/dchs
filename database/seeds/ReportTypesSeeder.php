<?php

use Illuminate\Database\Seeder;

class ReportTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => 'Отчет-1',
                'slug' => \App\Enums\ReportType::ANALYTICS_SPIASR
            ]
        ];

        foreach ($items as $item) {
            (new \App\Models\ReportType)->firstOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
