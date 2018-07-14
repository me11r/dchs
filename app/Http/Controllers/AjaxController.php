<?php

namespace App\Http\Controllers;


use App\Dictionary\Street;

class AjaxController extends AuthorizedController
{
    public function findStreet($txt, $area_id = null)
    {
        $streets = new Street();
        if ($area_id !== null) {
            $streets = $streets->where('city_area_id', $area_id);
        }
        $streets = $streets->where('name', 'like', '%' . $txt . '%');
        $streets = $streets->limit(30);
        $streets = $streets->get();
        return response()->json($streets->toJson());
    }
}