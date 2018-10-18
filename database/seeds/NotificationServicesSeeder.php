<?php

use Illuminate\Database\Seeder;

class NotificationServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('notification_services')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $userId = (new \App\User)->orderBy('id', 'ASC')->first()->id;

        (new \App\Models\NotificationService)->insert([
            ['id' => 1, 'name' => '112', 'code' => '112', 'head_user_id' => $userId],
            ['id' => 2, 'name' => 'ДВД 102', 'code' => '102', 'head_user_id' => $userId],
            ['id' => 3, 'name' => 'БСМП 103', 'code' => '103', 'head_user_id' => $userId],
            ['id' => 4, 'name' => 'Служба газа 104', 'code' => '104', 'head_user_id' => $userId],
            ['id' => 5, 'name' => 'Э\\сеть (277-98-42)', 'code' => 'electro', 'head_user_id' => $userId],
            ['id' => 6, 'name' => 'Водоканал (274-66-66)', 'code' => 'water', 'head_user_id' => $userId],
            ['id' => 7, 'name' => 'ЦМК (254-63-53)', 'code' => 'smk', 'head_user_id' => $userId],
            ['id' => 8, 'name' => 'ГУ Казселезащита', 'code' => 'gu_kaz', 'head_user_id' => $userId],
            ['id' => 9, 'name' => 'РОСО', 'code' => 'roso', 'head_user_id' => $userId],
            ['id' => 10, 'name' => 'AO Казавиаспас', 'code' => 'kaz_aviaserice', 'head_user_id' => $userId],
            ['id' => 11, 'name' => 'АО "Өртсөндіруші"', 'code' => 'ao_ort', 'head_user_id' => $userId],
        ]);
    }
}
