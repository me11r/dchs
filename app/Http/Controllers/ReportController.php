<?php

namespace App\Http\Controllers;

use App\AirRescueReport;
use App\Dictionary\FireObject;
use App\FormationReport;
use App\FormationTechReport;
use App\Models\Card112\Card112;
use App\Models\FireDepartmentResult;
use App\Models\FormationPersonsItem;
use App\Models\FormationTechItem;
use App\Models\IncidentType;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Reports\Report;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\FireObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use App\Services\ReportExport\Ticket101ExcelExport;
use App\Services\ReportExport\Ticket101WordExport;
use App\Ticket101;
use App\Ticket101ServicePlan;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpWord\PhpWord;
use Spipu\Html2Pdf\Html2Pdf;

class ReportController extends AuthorizedController
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

    public function getDaily()
    {
        $html = view('pdf/daily-report',
            (new Report($this->ticket101, $this->fireObject, $this->burntObject))->getReport()
        )->render();

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $date = date('d-m-Y');
        $file_name = "Суточный отчет - $date.pdf";

        $dompdf = new Dompdf();
        $dompdf->loadHTML($html, 'UTF-8');
        $dompdf->render();

        $dompdf->stream($file_name);
    }

    public function getOperational()
    {
        $data['yesterday'] = now()->subHours(24);
        $data['today'] = now();

        $card112_day = Card112::whereDate('created_at', '>=', $data['yesterday'])
            ->whereDate('created_at', '<=', $data['today']);
        $card101_day = Ticket101::whereDate('created_at', '>=', $data['yesterday'])
            ->whereDate('created_at', '<=', $data['today']);

        $card112_roadtrips = Ticket101ServicePlan::with(['service_type'])
            ->whereDate('created_at', '>=', $data['yesterday'])
            ->whereDate('created_at', '<=', $data['today'])
            ->whereNotNull('card112_id');

        $air_rescue_report = AirRescueReport::whereDate('created_at', '>=', $data['yesterday'])
            ->whereDate('created_at', '<=', $data['today']);

        $data['emergencies'] = $card112_day->count();
        $data['cards112'] = $card112_day->get();
        $data['card112_count'] = $card112_day->count();
        $data['card112_count_finished'] = $card112_day->count();
        $data['card101_count'] = $card101_day->count();
        $data['emergencies_human_in_danger'] = Card112::all();
        $data['emergencies_human_not_in_danger'] = Card112::all();
        $data['fires_count'] = $card101_day->count();
        $data['dead_count'] = $card112_day->sum('dead');
        $data['evacuated_count'] = $card112_day->sum('evacuated');
        $data['poisoned_by_gas_count'] = $card112_day->sum('poisoned');
        $data['hurt_count'] = $card112_day->sum('injured_hard');
        $data['saved_count'] = $card112_day->sum('saved');
        $data['card112_roadtrips'] = $card112_roadtrips->get();
        $data['mudflow_emergency_count'] = $card112_day->filterByServiceType('ГУ Казселезащита')->count();
        $data['roso_count'] = $card112_day->filterByServiceType('ГУ РОСО')->count();
        $data['cmk_count'] = $card112_day->filterByServiceType('ЦМК')->count();
        $data['flooding_count'] = $card112_day->filterByIncidentType('Подтопления')->count();
        /*$data['air_rescue_report'] = $air_rescue_report->whereHas('tech', function ($q){
            $q->status('action');
        })->first();*/

        $data['air_rescue_report_tech'] = $air_rescue_report->first() ? $air_rescue_report->first()->tech()->status('action')->get() : [];

        $html = view('pdf/operational-report', $data)->render();

        #test
//        return $html;
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $date = date('d-m-Y');
        $file_name = "Суточный отчет - $date.pdf";

        $dompdf = new Dompdf();
        $dompdf->loadHTML($html, 'UTF-8');
        $dompdf->render();

        $dompdf->stream($file_name);
    }

    public function getReport101($type)
    {
        if ($data = Cache::get('report101_data')) {
            /** @var FormationReport $formationReport */
            $formationReport = $data['report'];

            switch ($type) {
                case 'xls':
                    $ticket101Export = new Ticket101ExcelExport(
                        $formationReport,
                        $data['departments'],
                        $data['people'],
                        $data['tech'],
                        $data['sumArray']['people']
                    );

                    $writer = $ticket101Export->getXlsWriter();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . Carbon::parse($formationReport->created_at)->format('d-m-Y') . ' отчет.xls' . '"');
                    $writer->save('php://output');
                    break;
                case 'pdf':
                case 'docx':
                    $ticket101Export = new Ticket101WordExport(
                        $formationReport,
                        $data['departments'],
                        $data['people'],
                        $data['tech'],
                        $data['sumArray']['people']
                    );

                    // @todo PDF не работает корректно (но вроде оно и не нужно)
                    $writer = $ticket101Export->getWriter($type === 'pdf' ? 'PDF' : 'Word2007');
                    $fileName = Carbon::parse($formationReport->created_at)->format('d-m-Y') . " отчет.$type";
                    $writer->save(public_path($fileName));

                    return response()->download(public_path($fileName));
                    break;
            }

            return dd('Некорректный тип');
        }

        return dd('Кеш не заполнен');
    }

    public function getReport101Staff()
    {
        $staff = Staff::orderBy('name')->get();
        return view('reports.101.staff', compact('staff'));
    }

    public function postReport101Staff(Request $request)
    {
        $staff_id = $request->staff_id;
        $date_begin = $request->date_begin;
        $date_end = $request->date_end;

        $active = FormationPersonsItem::getStat($staff_id, $date_begin, $date_end, 'active');
        $inactive = FormationPersonsItem::getStat($staff_id, $date_begin, $date_end, 'inactive');

        $result['active_count'] = $active->count();
        $result['inactive_count'] = $inactive->count();
        $result['vacation_count'] = $inactive->where('rank', 'vacation')->count();
        $result['study_count'] = $inactive->where('rank', 'study')->count();
        $result['maternity_count'] = $inactive->where('rank', 'maternity')->count();
        $result['sick_count'] = $inactive->where('rank', 'sick')->count();
        $result['business_trip_count'] = $inactive->where('rank', 'business_trip')->count();
        $result['other_count'] = $inactive->where('rank', 'other')->count();

        return response()->json($result);
    }

    public function getReport101Vehicles()
    {
        $vehicles = Vehicle::with(['fireDepartment', 'vehicleType'])->get();

        return view('reports.101.vehicles', compact('vehicles'));
    }

    public function postReport101Vehicles(Request $request)
    {
        $vehicle_id = $request->vehicle_id;
        $date_begin = $request->date_begin;
        $date_end = $request->date_end;

        $active = FormationTechItem::getStat($vehicle_id, $date_begin, $date_end, 'action');
        $repair = FormationTechItem::getStat($vehicle_id, $date_begin, $date_end, 'repair');
        $reserve = FormationTechItem::getStat($vehicle_id, $date_begin, $date_end, 'reserve');

        $result['active_count'] = $active->count();
        $result['repair_count'] = $repair->count();
        $result['reserve_count'] = $reserve->count();

        return response()->json($result);
    }

    public function getReport101Emergency()
    {
        $reasons = FireObject::orderBy('name')->get();
        return view('reports.101.emergency', compact('reasons'));
    }

    public function postReport101Emergency(Request $request)
    {
        $date_begin = $request->date_begin;
        $date_end = $request->date_end;
        $reason_id = $request->reason_id;
        $result = Ticket101::getStat($date_begin, $date_end, $reason_id);

        return response()->json($result);
    }

    public function getReport112Emergency()
    {
        $reasons = IncidentType::orderBy('name')->get();
        return view('reports.112.emergency', compact('reasons'));
    }

    public function postReport112Emergency(Request $request)
    {
        $date_begin = $request->date_begin;
        $date_end = $request->date_end;
        $reason_id = $request->reason_id;
        $result = Card112::getStat($date_begin, $date_end, $reason_id);

        return response()->json($result);
    }

    public function getReport112Branches()
    {
        $incidentTypes = (new IncidentType)
            ->whereIn('name', ['Подтопления', 'Падение веток и деревьев'])
            ->orderBy('name')
            ->get();

        return view('reports.112.branches', compact('incidentTypes'));
    }

    public function getReport112BranchesExport(Request $request)
    {
        $fileName = 'Отчет:'
            . Carbon::parse($request->get('date_start'))->format('Y-m-d')
            . '_'
            . Carbon::parse($request->get('date_end'))->format('Y-m-d')
            . '.xls';

        $cards = (new Card112())
            ->where('incident_type_id', '=', $request->get('incident_type_id'))
            ->with(['cityArea'])
            ->get();

        $preparedToExport = [];
        foreach ($cards as $card) {
            if (!isset($preparedToExport[$card->cityArea->name])) {
                $preparedToExport[$card->cityArea->name] = [];
            }

            $preparedToExport[$card->cityArea->name][] = [
                '№' => $card->id,
                'Адрес' => $card->location,
                'Место происшествия' => $card->incident_place,
                'Причина' => $card->reason,
                'Пострадавшие / погибшие' => $card->injured . ' / ' . $card->dead,
                'Принятые меры' => $card->measures,
                'Количество задействованных сил и средств' => $card->resources,
                'Начало и завершение работ' =>
                    'Начало: ' . Carbon::parse($card->chronology_start_time)->format('H:i') .
                    ' / ' .
                    'Отработано' . Carbon::parse($card->chronology_end_time)->format('H:i')
            ];
        }

        $spreadsheet = new Spreadsheet();
        $writer = new Xls($spreadsheet);

        $index = 0;

        foreach ($preparedToExport as $key => $data) {
            if ($spreadsheet->getSheet($index)) {
                $spreadsheet->createSheet($index);
            }

            $spreadsheet->setActiveSheetIndex($index);
            $activeSheet = $spreadsheet->getActiveSheet();

            $activeSheet->setTitle($key);
            $activeSheet->fromArray(array_keys($data[0] ?? []), null, 'A1');
            $activeSheet->fromArray($data, null, 'A2');

            foreach (range('A', 'W') as $columnID) {
                $activeSheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $index++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function getForces(Request $request)
    {
        $today = Carbon::today();

        $data = [];

        $report_id = FormationReport::approved()->max('id');
        $data['reports'] = FormationTechReport::where('form_id', $report_id)
            ->has('items')
            ->orderBy('dept_id')
            ->with(['items', 'department'])
            ->get();

        foreach ($data['reports'] as $report_key => $report) {
            foreach ($report->items as $item_key => $tech_item) {
                $report->items[$item_key]['departures_count'] = FireDepartmentResult::
//                    where('fire_department_id', $report->dept_id)->
                    whereDate('created_at', $today)->
                    where('tech_id', $tech_item->id)->
                    whereNotNull('out_time')->
                    count()
                ;
                $report->items[$item_key]['status'] = Ticket101::whereHas('results', function ($q) use ($today, $tech_item){
                    $q->whereDate('created_at', $today)->
                        where('tech_id', $tech_item->id)->
                        whereNull('ret_time')->
                        whereNotNull('out_time');
                })
                    ->with(['results', 'fire_level'])
                    ->first();

                $report->items[$item_key]['address'] = $report->items[$item_key]['status']->location ?? null;
                $report->items[$item_key]['fire_rank'] = $report->items[$item_key]['status']->fire_level->name ?? null;
                $report->items[$item_key]['out_time'] = $report->items[$item_key]['status']->fire_level->name ?? null;

                if($report->items[$item_key]['status']){
                    $roadtripItem = $report->items[$item_key]['status']->results()->where('tech_id', $tech_item->id)->first();
                    if($roadtripItem){
                        $report->items[$item_key]['out_time'] = $roadtripItem->out_time;
                        $report->items[$item_key]['arrive_time'] = $roadtripItem->arrive_time;
                    }
                }
                else{
                    $report->items[$item_key]['out_time'] = null;
                    $report->items[$item_key]['arrive_time'] = null;
                }


                /*if($report->items[$item_key]['status']){

                }
                else{

                }*/

                    /*FireDepartmentResult::
//                    where('fire_department_id', $report->dept_id)->
                    whereDate('created_at', $today)->
                    where('tech_id', $tech_item->id)->
                    with(['ticket'])->
                    whereNotNull('out_time')->
                    first()->ticket ?? null;
                ;*/
            }
        }

        if($request->ajax()){
            return response()->json($data);
        }


        return view('reports.101.forces', $data);

    }
}
