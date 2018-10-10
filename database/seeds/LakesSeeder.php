<?php

use Illuminate\Database\Seeder;

class LakesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lakes = [
            [
                'name' => 'Озеро №6 б.р. Киши',
                'altitude' => '3600',
            ],
            [
                'name' => 'Озеро № 8 пр.Аксай',
                'altitude' => '3523',
            ],
            [
                'name' => 'Озеро №9 лев.Аксай',
                'altitude' => '3300',
            ],
            [
                'name' => 'Озеро №10 лев.Аксай',
                'altitude' => '3300',
            ],
            [
                'name' => 'Озеро №10 У.Алматы',
                'altitude' => '3641',
            ],
            [
                'name' => 'Озеро №11 У.Алматы',
                'altitude' => '3597',
            ],
            [
                'name' => 'Озеро №13 бис б.р Улкен Алматы',
                'altitude' => '3554',
            ],
            [
                'name' => 'озеро №1 Бис «Проходная»',
                'altitude' => 0,
            ],
            [
                'name' => 'озеро №3 Каргалы',
                'altitude' => 0,
            ],
        ];

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('morainic_lakes')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($lakes as $lake) {
            \App\Models\MorainicLake::create($lake);
        }
    }
}
