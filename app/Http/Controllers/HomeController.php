<?php

namespace App\Http\Controllers;

use App\Dictionary\TripResult;
use App\Models\Card112\Card112;
use App\Models\IncidentType;
use App\Services\CommonHelper;
use App\Services\DbHelper;
use App\Services\FileHelper;
use App\Services\Importer\Importer\CommonImporterTrait;
use App\Ticket101;
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
    public function getIndex(Request $request, CommonHelper $cp)
    {
        $previousYearBegin = now()->subYear()->startOfYear();
        $previousYearEnd = now()->subYear()->endOfYear();

        $results = [];
        $results112 = [];

        $currentYearBegin = now()->startOfYear();
        $currentYearEnd = now()->endOfYear();

        $date_begin = $request->get('date_begin', $previousYearBegin);
        $date_end = $request->get('date_end', now());

        $tripResults = TripResult::all();
        $incident_types = IncidentType::all();

//        $result['total101_previous'] = Ticket101::whereBetween('created_at', [$previousYearBegin, $previousYearEnd])->count();
//        $result['total112_previous'] = Card112::whereBetween('created_at', [$previousYearBegin, $previousYearEnd])->count();
//
//        $result['total101_current'] = Ticket101::whereBetween('created_at', [$currentYearBegin, $currentYearEnd])->count();
//        $result['total112_current'] = Card112::whereBetween('created_at', [$currentYearBegin, $currentYearEnd])->count();

        foreach ($tripResults as $tripResult) {
            $stat_previous = Ticket101::getStat($previousYearBegin, $previousYearEnd, $tripResult->id);
            $stat_current = Ticket101::getStat($currentYearBegin, $currentYearEnd, $tripResult->id);
            $results[] = [
                'title' => $tripResult->name,
                'emergency_count_previous' => $stat_previous['total'],
                'emergency_count_current' => $stat_current['total'],
                'emergency_different' => $cp->percent_difference($stat_previous['total'], $stat_current['total']),
                'hurt_previous' => $stat_previous['hurt'],
                'hurt_current' => $stat_current['hurt'],
                'hurt_different' => $cp->percent_difference($stat_previous['total'], $stat_current['total']),
                'died_previous' => $stat_previous['total'],
                'died_current' => $stat_current['total'],
                'died_different' => $cp->percent_difference($stat_previous['total'], $stat_current['total']),
            ];
        }

        foreach ($incident_types as $type) {
            $stat_previous_112 = Card112::getStat($previousYearBegin, $previousYearEnd, $type->id);
            $stat_current_112 = Card112::getStat($currentYearBegin, $currentYearEnd, $type->id);
            $results[] = [
                'title' => $type->name,
                'emergency_count_previous' => $stat_previous_112['total'],
                'emergency_count_current' => $stat_current_112['total'],
                'emergency_different' => $cp->percent_difference($stat_previous_112['total'], $stat_current_112['total']),
                'hurt_previous' => $stat_previous_112['total'],
                'hurt_current' => $stat_current_112['total'],
                'hurt_different' => $cp->percent_difference($stat_previous_112['total'], $stat_current_112['total']),
                'died_previous' => $stat_previous_112['total'],
                'died_current' => $stat_current_112['total'],
                'died_different' => $cp->percent_difference($stat_previous_112['total'], $stat_current_112['total']),
            ];
        }

        $results = json_encode($results);
        $results112 = json_encode($results112);

        $this->set('results', $results);
        $this->set('results112', $results112);
        $this->set('previous_year', $previousYearBegin->format('Y'));
        $this->set('current_year', $currentYearBegin->format('Y'));

        if($request->ajax()){
            return response()->json([
                'results' => $results,
                'results112' => $results112,
                'previous_year' => $previousYearBegin,
                'current_year' => $currentYearBegin,
            ], 200);
        }
    }

}
