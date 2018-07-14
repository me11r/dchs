<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 14:47
 */

class CityAreaSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        (new App\Dictionary\CityArea)->truncate();
        $regions = [
            'Алатауский',
            'Алмалинский',
            'Ауэзовский',
            'Бостандыкский',
            'Жетысуйский',
            'Медейский',
            'Турксибский',
        ];

        foreach ($regions as $region) {
            (new App\Dictionary\CityArea)->fill([
                'name' => $region,
            ])->save();
        }
    }
}