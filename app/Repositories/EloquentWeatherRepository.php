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


    public function createModel(Request $request): Weather
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ]);

        $fileName = time().'.'.$request->file->getClientOriginalExtension();

        $request->file->storeAs('weather',$fileName);

        return $this->model->create([
            'date' => $request->get('date'),
            'file' => $fileName
        ]);
    }
}