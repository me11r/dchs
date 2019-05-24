<?php

namespace App\Http\Controllers;

use App\Dictionary\CityArea;
use App\ReportIsk;
use App\Services\ReportExport\ReportIskWordExport;
use App\Ticket101;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportIskController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $records = ReportIsk::has('ticket_101')
            ->whereHas('ticket_101.trip_result', function ($q) {
                $q->whereIn('emergency_code', [115,116]);
            })
            ->with('ticket_101');

        $data['date_from'] = $request->input('date_from', Carbon::create('2019', '01', '01')->format('Y-m-d'));
        $data['date_to'] = $request->input('date_to', null);
        $data['city_areas'] = CityArea::all();
        $data['search'] = trim($request->input('search', null));
        $data['city_area'] = $request->input('city_area', null);

        if ($data['date_from'] && $data['date_to']) {
            $records = $records->whereHas('ticket_101', function ($q) use ($data) {
               $q->whereBetween('custom_created_at', [$data['date_from'], $data['date_to']]);
            });
        }

        if($data['city_area']){

            $records = $records
                ->whereHas('ticket_101', function ($q) use ($data) {
                    $q->where('city_area_id', $data['city_area']);
                });
        }

        if($data['search']){
            if(is_numeric($data['search'])){
                $records = $records->where('id', $data['search']);
            }
            else{
                try{
                    $date = Carbon::parse(str_replace(['/', '.'],'-',$data['search']));
                }
                catch (\Exception $e){
                    $date = null;
                }
                $records = $records
                    ->whereHas('ticket_101', function ($t) use ($data, $date) {
                        $t->where('location', "like", "%{$data['search']}%")
                            ->orWhereDate('custom_created_at', $date)
                            ->orWhereHas('city_area', function ($q) use ($data){
                                $q->where('name', "like", "{$data['search']}%");
                            });
                    });

            }
        }


        $data['per_page'] = $request->input('per_page', 20);
        $data['records'] = $records
            ->orderBy('created_at', 'desc')
            ->paginate($data['per_page']);
        return view('reports.isk.index', $data);
    }

    public function edit($id)
    {
        $data = [];
        $record = ReportIsk::find($id);
        $data['record'] = $record;
        $data['records'] = $record->getInfo(true);
        $data['model'] = new ReportIsk();
        return view('reports.isk.edit', $data);
    }

    public function export($id)
    {
        $data = [];
        $ticket = Ticket101::find($id);
        if (!$ticket->report_isk) {
            $record = $ticket->report_isk()->create(['ticket101_id' => $ticket->id]);
        }
        else {
            $record = $ticket->report_isk;
        }
        $data['record'] = $record;
        $data['records'] = $record->getInfo(false);
        $report = new ReportIskWordExport($data);
        return $report->export();
    }

    public function update(Request $request, $id)
    {
        $record = ReportIsk::find($id);
        $all = $request->all();
        $record->update($all);
        return back();
    }
}
