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

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $data['object_classes'] = ObjectClassification::all();
        $data['year'] = now()->format('Y');
        $data['fireDepartments'] = FireDepartment::all();
        $data['rideTypes'] = RideType::all();
        $normTypes = NormType::all()->map(function ($item) {
            return $item->name;
        })->toArray();

        $normTypes = array_merge($normTypes, [
            'РКШУ',
            'ТСУ',
            'ПТУ',
            'ПТЗ',
            'ТДК',
            'Учения',
        ]);

        $data['normTypes'] = $normTypes;

        $drillTypes = DrillType::whereIn('name', ['ПТЗ','ПТУ'])->get();

        foreach ($drillTypes as $type) {

            foreach (range(1, 12) as $month) {

                $data['counts'][$type->name]['per_month'][$month] = Ticket101::whereYear('created_at', now()->format('Y'))
                    ->whereMonth('created_at', $month)
                    ->where('drill_type_id',$type->id)
                    ->count();

                foreach ($data['object_classes'] as $object_class) {

                    $data['records'][$type->name][$object_class->name][$month] = Ticket101::whereYear('created_at', now()->format('Y'))
                        ->where('object_classification_id', $object_class->id)
                        ->whereMonth('created_at', $month)
                        ->where('drill_type_id',$type->id)->count();

                    $data['counts'][$type->name]['per_object'][$object_class->name] = Ticket101::whereYear('created_at', now()->format('Y'))
                        ->where('drill_type_id',$type->id)
                        ->where('object_classification_id', $object_class->id)
                        ->count();
                }
            }
        }

        Cache::put('report101_object_classes', $data, 3600);


        /*если вдруг запись не была создана кроном, создаем вручную*/
        $hoursNow = now()->format('H');
        if($hoursNow >= 8){
            Analytics101::firstOrCreate(['date' => today()]);
        }

        $data['items'] = Analytics101::orderBy('id', 'desc')
            ->paginate($perPage);
        $data['per_page'] = $perPage;
        $data['user'] = Auth::user();

//        $data['reports112'] = Card112::paginate($perPage)->groupBy

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
