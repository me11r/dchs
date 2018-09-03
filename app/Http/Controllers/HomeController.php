<?php

namespace App\Http\Controllers;

use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\Street;
use App\Models\WallMaterial;
use App\Services\Importer\Importer\CommonImporterTrait;
use Carbon\Carbon;
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
        $time = Carbon::now();
        $some_date = Carbon::create($time->year, $time->month, $time->day - 1)->isYesterday();
        $morning_begin = Carbon::create($time->year, $time->month, $time->day, 8, 0, 0); //set time to 08:00
        $morning_end = Carbon::create($time->year, $time->month, $time->day, 9, 0, 0); //set time to 09:00
        $evening_begin = Carbon::create($time->year, $time->month, $time->day, 18, 0, 0); //set time to 18:00
        $evening_end = Carbon::create($time->year, $time->month, $time->day, 19, 0, 0); //set time to 19:00
        $is_beetween = $time->between($evening_begin, $evening_end, true);

        if($time->between($morning_begin, $morning_end, true)) {
            //true
        } else {
            //false
        }
    }
}
