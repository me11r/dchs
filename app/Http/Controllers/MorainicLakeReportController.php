<?php

namespace App\Http\Controllers;

use App\Models\MorainicLakeReport;
use App\Models\MorainicLakeSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MorainicLakeReportController extends Controller
{
    private $view = 'morainic-lakes-reports';

    public function index($date)
    {
        $changeFormat = Carbon::parse($date)->format('Y/m/d');
        $lakesSumRaw = MorainicLakeSummary::where('date', $changeFormat);
        $lakesSummary = $lakesSumRaw->get();
        $report = MorainicLakeReport::where('date', $changeFormat)->first();
        $note = $report->note ?? null;
        return view("{$this->view}.show", compact('lakesSummary', 'date', 'note', 'lakesSumRaw'));
    }

    public function update(Request $request, $date)
    {
        $changeFormat = Carbon::parse($date)->format('Y/m/d');

        MorainicLakeReport::updateOrCreate([
            'date' => $changeFormat,
        ], [
            'date' => $changeFormat,
            'note' => $request->note,
        ]);

        return back();
    }
}
