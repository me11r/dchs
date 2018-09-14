<?php

use Illuminate\Database\Seeder;

class CoordinatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $geo = new \App\Services\GeoService();
        $totalCount = \App\Models\Building::whereNull('lat')->count();
        $this->command->line("Records to process:{$totalCount}");
        foreach (\App\Models\Building::whereNull('lat')->get() as $key => $item) {
            $address = '';
            $address .= $item->city_area->name.' район ';

            if($item->street_id){
                $address .= "{$item->street->name} {$item->building_number}";
            }
            if($item->city_micro_area_id){
                $address .= " {$item->city_micro_area->name}";
            }

            if($address){
                $coordinates = $geo->getCoordinates($address);
                if($coordinates){
                    $item->lat = $coordinates['lat'];
                    $item->long = $coordinates['long'];
                    $item->save();
                }
            }

            if($key % 1000 == 0){
                $this->command->line("{$key}/{$totalCount}");
            }
        }
    }
}
