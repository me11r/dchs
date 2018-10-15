<?php

namespace App\Http\Controllers;

use App\Models\Card112\Card112;
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
    public function getIndex(Request $request)
    {
        $previousYearBegin = now()->subYear()->startOfYear();
        $previousYearEnd = now()->subYear()->endOfYear();

        $currentYearBegin = now()->startOfYear();
        $currentYearEnd = now()->endOfYear();

        $date_begin = $request->get('date_begin', $previousYearBegin);
        $date_end = $request->get('date_end', now());
        $sum = Ticket101::getStat($date_begin, $date_end);

        $result['total101_previous'] = Ticket101::whereBetween('created_at', [$previousYearBegin, $previousYearEnd])->count();
        $result['total112_previous'] = Card112::whereBetween('created_at', [$previousYearBegin, $previousYearEnd])->count();

        $result['total101_current'] = Ticket101::whereBetween('created_at', [$currentYearBegin, $currentYearEnd])->count();
        $result['total112_current'] = Card112::whereBetween('created_at', [$currentYearBegin, $currentYearEnd])->count();

    }

}
