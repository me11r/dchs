<?php

namespace App\Http\Controllers;

use App\Analytics101;
use App\Analytics101Item;
use App\Dictionary\TripResult;
use App\Reports\Report;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\FireObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use App\Services\ReportExport\DailyWordExport;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        /*если вдруг запись не была создана кроном, создаем вручную*/
        $hoursNow = now()->format('H');
        if($hoursNow >= 8){
            Analytics101::firstOrCreate(['date' => today()]);
        }

        $data['items'] = Analytics101::orderBy('id', 'desc')
            ->paginate($perPage);
        $data['per_page'] = $perPage;
        return view('analytics.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $data['record'] = Analytics101::with([
            'items',
            'items.trip_result',
            'items.ticket101',
        ])->find($id);

        $data['firstDate'] = Carbon::parse($data['record']->date)->addHours(7)->format('d.m.Y H:i');
        $data['secondDate'] = Carbon::parse($data['firstDate'])->addDay()->format('d.m.Y H:i');

        $data['tripResults'] = TripResult::all();

        return view('analytics.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $all = $request->all();

        foreach ($request->input('text', []) as $item_id => $text) {
            $analyticsItem = Analytics101Item::find($item_id);

            $text = preg_replace("/<p[^>]*>[\s|&nbsp;]*<\/p>/", '', $text); //убираем пустые параграфы
            $analyticsItem->text = str_replace('<br>', "<br/>", $text);
            $analyticsItem->save();

        }

        return redirect()->route('reports.analytics101.index');

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

        $dailyWordExport = new DailyWordExport(
            $data
        );
        $writer = $dailyWordExport->getWriter('Word2007');
        $fileName = 'Суточный отчет 101 - '.date('d-m-Y', strtotime($analytic->date)). '.docx';
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }
}
