<?php

namespace App\Http\Controllers;


use App\Dictionary\Street;
use Illuminate\Http\Request;

class AjaxController extends AuthorizedController
{
    public function findStreet(Request $request, $area_id = null)
    {
        $streets = Street::with('area');
        if ($area_id !== null) {
            $streets = $streets->where('city_area_id', $area_id);
        }
        $txt = $request->get('q', '');
        $txt = str_replace('%', '', $txt);

        $streets = $streets
            ->where('name', 'like', $txt . '%')
            ->limit(30);

        $streets = $streets->get();
        return response()->json($streets, 200, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }
}