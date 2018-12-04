<?php

namespace App\Http\Controllers;

use App\Analytics101;
use App\Analytics101Item;
use App\Dictionary\TripResult;
use App\Reports\Report;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\FireObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
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
        $report = (new Report($this->ticket101, $this->fireObject, $this->burntObject))->getReport();

        $data['record'] = Analytics101::with([
            'items',
            'items.trip_result',
        ])->find($id);

        if(isset($report['tripResults']) && count($report['tripResults'])){
            foreach ($report['tripResults'] as $title => $items) {
                foreach ($items as $reportItem) {
                    $data['record']->items()->firstOrCreate(
                        ['ticket101_id' => $reportItem['id']],
                        [
                        'text' => $reportItem['analytics'],
                        'trip_result_id' => $reportItem['trip_result_id'],
                        'ticket101_id' => $reportItem['id'],
                    ]);
                }
            }
        }

        $data['record'] = Analytics101::with([
            'items',
            'items.trip_result',
        ])->find($id);

        $data['tripResults'] = TripResult::all();

        return view('analytics.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $record = Analytics101::with(['items'])->find($id);

        $all = $request->all();

        foreach ($request->input('text', []) as $item_id => $text) {
            $analyticsItem = Analytics101Item::find($item_id);
            $analyticsItem->text = $text;
            $analyticsItem->save();
        }

        return redirect()->route('reports.analytics101.index');

    }

    public function delete(Request $request, $id)
    {
        Analytics101::destroy($id);
        return back();
    }
}
