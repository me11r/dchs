<?php

class MudflowProtectionSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        if (\App\Models\MudflowProtection::count() === 0) {
            $rivers = \App\Models\River::get();
            foreach ($rivers as $river) {
                $gauging_stations = \App\Models\GaugingStation::where('river_id', '=', $river->id)->get();
                foreach ($gauging_stations as $gauging_station) {
                    \App\Models\MudflowProtection::create([
                        'information' => '',
                        'gauging_station_id' => $gauging_station->id,
                        'water_flow_rate' => 0,
                        'critical_water_flow_rate' => 0,
                        'turbidity_of_water' => 0,
                        'max_turbidity_of_water' => 0,
                        'air_temperature' => 0,
                        'water_temperature' => 0,
                        'precipitation' => 0,
                        'height_of_snow' => 0,
                        'weather' => '',
                        'comment' => '',
                    ]);
                }
            }
        }
    }
}