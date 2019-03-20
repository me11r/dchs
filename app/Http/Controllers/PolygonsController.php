<?php

namespace App\Http\Controllers;

use App\Dictionary\CityArea;
use Illuminate\Http\Request;

class PolygonsController extends Controller
{
    public function index(){
        return view('polygons.index', [
            'areas' => (new CityArea())->get()->toArray()
        ]);
    }
}
