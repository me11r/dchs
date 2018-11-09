<?php

namespace App\Repositories\Contracts;

use App\Models\Weather;
use Illuminate\Http\Request;

interface WeatherInterface
{
    public function createModel(Request $request): Weather;
    public function updateModel(Request $request, $id): Weather;

}