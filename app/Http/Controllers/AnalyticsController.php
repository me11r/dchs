<?php

namespace App\Http\Controllers;

use App\Analytics101;
use App\Analytics101Item;
use App\Dictionary\TripResult;
use App\DrillType;
use App\FireDepartment;
use App\Models\Card112\Card112;
use App\NormType;
use App\ObjectClassification;
use App\Reports\Report;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\FireObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use App\RideType;
use App\Services\ReportExport\DailyWordExport;
use App\Ticket101;
use Carbon\Carbon;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    protected $ticket101;
    protected $fireObject;
    protected $burntObject;

    public function __construct(
        Ticket101Interface $ticket101,
        FireObjectInterface $fireObject,
        BurntObjectInterface $burntObject
    )
    {
        $this->ticket101 = $ticket101;
        $this->fireObject = $fireObject;
        $this->burntObject = $burntObject;
        parent::__construct();
    }

    public function edit(Request $request, $id)
    {
        $data['record'] = Analytics101::with([
            'items',
            'items.trip_result',
            'items.ticket101',
        ])->find($id);

        $data['firstDate'] = Carbon::parse($data['record']->date)->addHours(7)->format('d.m.Y H:i');
        $data['secondDate'] = Carbon::parse($data['record']->date)->addDay()->addHours(7)->format('d.m.Y H:i');

        $data['tripResults'] = TripResult::all();

        return view('analytics.edit', $data);
    }

    public function update(Request $request, $id)
    {
        foreach ($request->input('text', []) as $item_id => $text) {
            $analyticsItem = Analytics101Item::find($item_id);

            $text = preg_replace("/<p[^>]*>[\s|&nbsp;]*<\/p>/", '', $text); //убираем пустые параграфы
            $analyticsItem->text = str_replace('<br>', "<br/>", $text);
            $analyticsItem->save();

        }

        return redirect('reports/daily-reports/101');
    }

    public function delete(Request $request, $id)
    {
        Analytics101::destroy($id);
        return back();
    }

    public function word($id)
    {
        $analytic = Analytics101::find($id);
        $data = (new Report($this->ticket101, $this->fireObject, $this->burntObject))->getReport($analytic->date);

        if (!$data) {
            return back()->with('_message', [
                'type' => 'success',
                'text' => 'Строевая записка не утверждена'
            ]);
        }

        $dailyWordExport = new DailyWordExport(
            $data
        );
        $writer = $dailyWordExport->getWriter('Word2007');
        $fileName = 'Суточный отчет 101 - '.date('d-m-Y', strtotime($analytic->date)). '.docx';
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }
}
