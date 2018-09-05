<?php

namespace App\Http\Controllers;

use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\Street;
use App\FireDepartment;
use App\Models\Staff;
use App\Models\WallMaterial;
use App\Services\ChunkedImporter\ChunkedImporter;
use App\Services\Importer\Importer\CommonImporterTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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
