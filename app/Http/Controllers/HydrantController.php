<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\Models\Hydrant;
use Illuminate\Support\Facades\View;
use App\Http\Resources\HydrantResource;

class HydrantController extends Controller
{

    public function index()
    {
        return View::make('hydrant.index')
            ->with('fireDepartments', collect(FireDepartment::all(['id', 'title']))->toArray())
            ->with('model', new HydrantResource(new Hydrant()))
            ->render();
    }
}
