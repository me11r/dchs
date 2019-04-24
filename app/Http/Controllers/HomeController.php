<?php

namespace App\Http\Controllers;

use App\CallInfo;
use App\Dictionary\TripResult;
use App\Models\Card112\Card112;
use App\Models\IncidentType;
use App\Models\MudflowProtection;
use App\Models\Quake;
use App\Models\River;
use App\Models\Weather;
use App\NormPsp;
use App\Services\CommonHelper;
use App\Services\DbHelper;
use App\Services\FileHelper;
use App\Services\Importer\Importer\CommonImporterTrait;
use App\Ticket101;
use App\Ticket101Other;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

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
    public function getIndex(Request $request, CommonHelper $h)
    {
        $data['call_infos'] = [
            'count_101' => CallInfo::shiftRecords()->sum('count_101'),
            'count_112' => CallInfo::shiftRecords()->sum('count_112'),
        ];

        $data['card_infos'] = [
            'count_101_real' => Ticket101::shiftRecords()->real()->count(),
            'count_101_drill' => Ticket101::shiftRecords()->drill()->count(),
            'count_101_norm_psp' => NormPsp::shiftRecords()->count(),
            'count_101_other_rides' => Ticket101Other::shiftRecords()->count(),
            'count_112' => Card112::shiftRecords()->count(),
        ];

        $quake = Quake::select('*')->latest()->first();
        $quakeString = '';
        if ($quake) {
            $quakeString .= "Описание: {$quake->description}, ";
            $quakeString .= "дата и время Алматинского времени: {$quake->date_almaty}, ";
            $quakeString .= "эпицентр: {$quake->epicenter}, ";
            $quakeString .= "магнитуда: {$quake->mpv}, ";
            $quakeString .= "глубина: {$quake->deep}, ";
            $quakeString .= "сведения об ощутимости: {$quake->information}, ";
            $quakeString .= "энергетический класс: {$quake->energy_class}, ";
            $quakeString .= "координаты эпицентра: {$quake->coordinates}. ";
            $quakeString .= "Дата заполнения: {$quake->created_at->format('d.m.Y H:i')}, ";
        }

        $weather = Weather::select('*')->latest()->first();
        $weatherString = '';

        if($weather) {
            $weatherString .= "Прогноз погоды: {$weather->forecast_city1}, дата заполнения: {$weather->created_at->format('d.m.Y')}";
        }

        $mudflowProtectionLatestDate = MudflowProtection::max('date');
        $mudflowProtectionRecords = MudflowProtection::where('date', $mudflowProtectionLatestDate)
            ->get()
            ->keyBy('gauging_station_id');

        $data['services_infos'] = [
            'SOME' => $quakeString,
            'weather' => $weatherString,
            'mudflow' => $mudflowProtectionRecords,
        ];

        $data['rivers'] = River::with([
            'gaugingStations',
            'gaugingStations.mudflowProtection'
        ])->get();

        if($request->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('home.index', $data);
    }

}
