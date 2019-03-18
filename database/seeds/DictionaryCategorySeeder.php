<?php

use Illuminate\Database\Seeder;

class DictionaryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => '101', 'sort_order' => 1],
            ['name' => '112', 'sort_order' => 2],
            ['name' => 'Личный состав 101', 'sort_order' => 3],
            ['name' => 'Личный состав 112', 'sort_order' => 4],
            ['name' => 'Общий раздел', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            \App\DictionaryCategory::updateOrCreate(['name' => $category['name']],$category);
        }
    }
}
