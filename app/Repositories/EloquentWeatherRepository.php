<?php

namespace App\Repositories;

use App\Models\Weather;
use App\Repositories\Contracts\WeatherInterface;
use Illuminate\Http\Request;

class EloquentWeatherRepository extends Repository implements WeatherInterface
{

    public function model()
    {
        return Weather::class;
    }

    public function stormRecords($hasStorm)
    {
        if($hasStorm === "0") {
            return $this->model->whereNull('storm_warning_text');
        }

        if($hasStorm === "1") {
            return $this->model->whereNotNull('storm_warning_text');
        }

        return $this->model;
    }


    public function createModel(Request $request): Weather
    {
        $request->validate([
//            'file' => 'required|file|max:5120',
        ]);

        return $this->model->create([
            'date' => $request->date,
            'number' => $request->number,
            'weather_now' => $request->weather_now,
            'water_now' => $request->water_now,
            'radiation_now' => $request->radiation_now,
            'atmosphere_now' => $request->atmosphere_now,
            'address' => $request->address,
            'filial_director' => $request->filial_director,
            'executor' => $request->executor,
            'forecast_area' => $request->forecast_area,
            'forecast_city1' => $request->forecast_city1,
            'city1_abs_max' => $request->city1_abs_max,
            'city1_abs_min' => $request->city1_abs_min,
            'forecast_city2' => $request->forecast_city2,
            'city2_abs_max' => $request->city2_abs_max,
            'city2_abs_min' => $request->city2_abs_min,
            'forecast_water' => $request->forecast_water,
            'forecast_atmosphere' => $request->forecast_atmosphere,
            'note' => $request->note,
            'storm_warning_number' => $request->storm_warning_number,
            'storm_warning_date' => $request->storm_warning_date,
            'storm_warning_text' => $request->storm_warning_text,
        ]);
    }

    public function updateModel(Request $request, $id): Weather
    {
        return $this->model->updateOrCreate(['id' => $id],[
            'number' => $request->number,
            'weather_now' => $request->weather_now,
            'water_now' => $request->water_now,
            'radiation_now' => $request->radiation_now,
            'atmosphere_now' => $request->atmosphere_now,
            'address' => $request->address,
            'filial_director' => $request->filial_director,
            'executor' => $request->executor,
            'forecast_area' => $request->forecast_area,
            'forecast_city1' => $request->forecast_city1,
            'city1_abs_max' => $request->city1_abs_max,
            'city1_abs_min' => $request->city1_abs_min,
            'forecast_city2' => $request->forecast_city2,
            'city2_abs_max' => $request->city2_abs_max,
            'city2_abs_min' => $request->city2_abs_min,
            'forecast_water' => $request->forecast_water,
            'forecast_atmosphere' => $request->forecast_atmosphere,
            'note' => $request->note,
            'storm_warning_number' => $request->storm_warning_number,
            'storm_warning_date' => $request->storm_warning_date,
            'storm_warning_text' => $request->storm_warning_text,
        ]);
    }
}