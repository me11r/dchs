<?php

namespace App\Http\Controllers;

use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\Street;
use App\Models\WallMaterial;
use App\Services\Importer\Importer\CommonImporterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use CommonImporterTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
    }
}
