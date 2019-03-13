<?php

namespace App\Http\Controllers;

use App\AirRescueReport;
use App\Analytics101;
use App\CallInfo;
use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireObject;
use App\Dictionary\TripResult;
use App\DrillType;
use App\FireDepartment;
use App\FloodingReason;
use App\FormationReport;
use App\FormationTechReport;
use App\Models\Card112\Card112;
use App\Models\DailyReportPerson;
use App\Models\EmergencySituation;
use App\Models\FireDepartmentResult;
use App\Models\FormationPersonsItem;
use App\Models\FormationTechItem;
use App\Models\IncidentType;
use App\Models\OperationalPlan;
use App\Models\Quake;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Models\Weather;
use App\NormPsp;
use App\NormType;
use App\ObjectClassification;
use App\OperationalCard;
use App\ReportCache;
use App\Reports\Report;
use App\Reports\Report101DrillRides;
use App\Reports\Report101EmergencyRescueGu;
use App\Reports\Report101ObjectClass;
use App\Reports\Report101OtherRides;
use App\Reports\Report101Resources;
use App\Reports\Report101WaterConsumption;
use App\Reports\Report112;
use App\Reports\Report112Emergency;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\FireObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use App\RideType;
use App\Services\CommonHelper;
use App\Services\FileHelper;
use App\Services\ReportExport\Daily112WordExport;
use App\Services\ReportExport\DailyWordExport;
use App\Services\ReportExport\ReportAvalanches;
use App\Services\ReportExport\ReportDisease;
use App\Services\ReportExport\ReportElevators;
use App\Services\ReportExport\ReportForcesExcelExport;
use App\Services\ReportExport\ReportQuakes;
use App\Services\ReportExport\Ticket101ChronologyExcelExport;
use App\Services\ReportExport\Ticket101DrillRidesExcelExport;
use App\Services\ReportExport\Ticket101EmergencyRescueGuExcel;
use App\Services\ReportExport\Ticket101ExcelExport;
use App\Services\ReportExport\Ticket101ObjectClassExcelExport;
use App\Services\ReportExport\Ticket101OtherRidesExcelExport;
use App\Services\ReportExport\Ticket101PeriodExcelExport;
use App\Services\ReportExport\Ticket101ResourcesExcelExport;
use App\Services\ReportExport\Ticket101WaterConsumptionExcelExport;
use App\Services\ReportExport\Ticket101WordExport;
use App\Services\ReportExport\Ticket112EmergencyExcelExport;
use App\Services\ReportExport\Ticket112EmergencyWordExport;
use App\Services\ReportExport\Ticket112PeriodExcelExport;
use App\SirenSpeechTech;
use App\Ticket101;
use App\Ticket101Other;
use App\Ticket101ServicePlan;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\WriterInterface;
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

        $callInfo = CallInfo::latest()->first();

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
        $data['siren_speech_tech'] = SirenSpeechTech::latest()->first();
        $data['weather_forecast'] = Weather::latest()->first();
        $data['emergency_situations'] = EmergencySituation::whereDate('created_at', '=', $data['today'])->get();
        $data['call_info'] = $callInfo;

        $data['trip_results101'] = [];

        foreach (TripResult::all() as $tripResult) {
            $data['trip_results101'][$tripResult->name] = $card101_day->where('trip_result_id', $tripResult->id)->count();
        }

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
                        $data['sumArray']['people'],
                        $data
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
                        $data['sumArray']['people'],
                        $data
                    );

                    // @todo PDF не работает корректно (но вроде оно и не нужно)
                    $writer = $ticket101Export->getWriter($type === 'pdf' ? 'PDF' : 'Word2007');
                    $fileName = Carbon::parse($formationReport->created_at)
                            ->addDay()
                            ->format('d-m-Y') . " отчет.$type";
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
        $reasons = TripResult::orderBy('name')->get();
        $burntObjects = BurntObject::orderBy('name')->get();
        $cityAreas = CityArea::orderBy('name')->get();
        return view('reports.101.emergency', compact('reasons', 'burntObjects', 'cityAreas'));
    }

    public function postReport101Emergency(Request $request)
    {
        $date_begin = $request->date_begin;
        $date_end = $request->date_end;
        $result_id = $request->result_id;
        $burnt_id = $request->burnt_id;
        $city_area_id = $request->city_area_id;

        $result = Ticket101::getDetailedStat($date_begin, $date_end, $result_id, $burnt_id, $city_area_id);

        return response()->json($result);
    }

    public function getReport112Emergency()
    {
        $reasons = IncidentType::orderBy('name')->get();
        return view('reports.112.emergency', compact('reasons'));
    }

    ///reports/112/emergency
    /// Отчет по карточке 112 за период (фильтр)
    public function postReport112Emergency(Request $request)
    {
        $date_begin = $request->date_begin;
        $date_end = $request->date_end;
        $reason_id = $request->reason_id;
        $cityAreaId = $request->city_area_id;
        $emergencyNameId = $request->emergency_name_id;

        $result = Card112::getDetailedStat($date_begin, $date_end, $reason_id, $cityAreaId,$emergencyNameId);

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

    //Отчет (падение веток и деревьев, подтопления)
    public function getReport112BranchesExport(Request $request)
    {
        $dateStart = Carbon::parse($request->get('date_start'))->format('Y-m-d');
        $dateEnd = Carbon::parse($request->get('date_end'))->format('Y-m-d');
        $emergency_name_id = $request->emergency_name_id;
        $cityAreaId = $request->city_area_id;

        $dateStartHuman = Carbon::parse($request->get('date_start'))->format('d.m.Y');
        $dateEndHuman = Carbon::parse($request->get('date_end'))->format('d.m.Y');

        $fileName = 'Отчет:'
            . $dateStart
            . '_'
            . $dateEnd
            . '.xls';

        $cards = (new Card112())
            ->skipNullValue('additional_incident_type_id',  $request->get('incident_type_id'))
            ->skipNullValue('emergency_name_id',$emergency_name_id)
            ->skipNullValue('city_area_id',$cityAreaId)
            ->whereBetween('custom_created_at', [$dateStart,$dateEnd])
            ->with(['cityArea'])
            ->get();

        $incidentType = IncidentType::find($request->get('incident_type_id', 1));

        $preparedToExport = [];
        foreach ($cards as $card) {
            if (!isset($preparedToExport[$card->cityArea->name])) {
                $preparedToExport[$card->cityArea->name] = [];
            }

            if($card->incident->name == 'Падение веток и деревьев') {
                $preparedToExport[$card->cityArea->name][] = [
                    '№' => $card->id,
                    'Адрес' => $card->location,
                    'Дата происшествия' => $card->created_at->format('d.m.Y'),
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
            elseif ($card->incident->name == 'Подтопления') {
                $preparedToExport[$card->cityArea->name][] = [
                    '№' => $card->id,
                    'Адрес' => $card->location,
                    'Кол-во проживающих' => $card->living_count ?? 0,
                    'Дата происшествия' => $card->created_at->format('d.m.Y'),
                    'Место подтопления' => $card->flooding_place->name ?? null,
                    'Причина подтопления' => $card->flooding_reason->name ?? null,
                    'Принятые меры' => $card->measures,
                    'Количество задействованных сил и средств' => $card->resources,

                    'Начало и завершение работ' =>
                        'Начало: ' . Carbon::parse($card->chronology_start_time)->format('H:i') .
                        ' / ' .
                        'Отработано' . Carbon::parse($card->chronology_end_time)->format('H:i')
                ];
            }
            else {
                $preparedToExport[$card->cityArea->name][] = [
                    '№' => $card->id,
                    'Адрес' => $card->location,
                    'Кол-во проживающих' => $card->living_count,
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
        }
        //проставляем номера 1,2,3 и тд
        foreach ($preparedToExport as $cityArea => $arrays) {
            $indx = 0;
            foreach ($arrays as $key => $array) {
                $preparedToExport[$cityArea][$key]['№'] = ++$indx;
            }
        }

        $total = 0;
        foreach ($preparedToExport as $item) {
            $total += count($item);
        }

        if($request->ajax()) {
            return response()->json(['data' => $preparedToExport, 'total' => $total]);
        }

        $spreadsheet = new Spreadsheet();
        $writer = new Xls($spreadsheet);

        $rowIndex = 1;
        $activeSheet = $spreadsheet->getActiveSheet();

        if($incidentType->name === 'Подтопления') {
            $floodingReasonsCountArr = [];
            $floodingReasonsCountStr = '';
            foreach (FloodingReason::all() as $floodingReason) {
                $floodingReasonsCountArr[$floodingReason->name] = $cards->filter(function ($q) use ($floodingReason) {
                    return $q->flooding_reason_id === $floodingReason->id;
                })->count();

                if($floodingReasonsCountArr[$floodingReason->name] !== 0) {
                    $floodingReasonsCountStr .= "$floodingReason->name – {$floodingReasonsCountArr[$floodingReason->name]} случаев(-ай); ";
                }
            }
            $activeSheet
                ->getCell('D' . $rowIndex)
                ->setValue("Информация")
                ->getStyle()
                ->getFont()
                ->setBold(true);

            $rowIndex++;

            $activeSheet
                ->getCell('C' . $rowIndex)
                ->setValue("по подтоплениям по г. Алматы в период с {$dateStartHuman}г. по {$dateEndHuman}г.,")
                ->getStyle()
                ->getFont()
                ->setBold(true);

            $rowIndex++;

            $activeSheet
                ->getCell('C' . $rowIndex)
                ->setValue("зафиксировано {$cards->count()} случаев подтоплений участков, из них: {$floodingReasonsCountStr}. ")
                ->getStyle()
                ->getFont()
                ->setBold(true);

            $rowIndex += 3;
            foreach ($preparedToExport as $key => $data) {
                $totalPerArea = count($data);
                $activeSheet->getCell('E' . $rowIndex)->setValue("$key - {$totalPerArea}")->getStyle()->getFont()->setBold(true);

                $activeSheet->fromArray(array_keys($data[0] ?? []), null, 'A' . ($rowIndex + 1));
                $activeSheet->fromArray($data, null, 'A' . ($rowIndex + 2));

                $activeSheet
                    ->getStyle('A'.($rowIndex + 1).':I'. $activeSheet->getHighestRow())
                    ->applyFromArray(Ticket101ExcelExport::HStyle);

                $activeSheet
                    ->getStyle('A'.($rowIndex + 1).':I'. ($rowIndex + 1))
                    ->getFont()
                    ->setBold(true);

                $rowIndex = $activeSheet->getHighestRow();
                $rowIndex += 3;
            }

            $activeSheet->getCell('B' . $rowIndex)
                ->setValue("Всего на номер «112» поступило {$cards->count()} сообщений о подтоплениях")
                ->getStyle()
                ->getFont()
                ->setBold(true);

            $activeSheet->getColumnDimension('A')->setWidth(3);
            $activeSheet->getColumnDimension('B')->setWidth(20);
            $activeSheet->getColumnDimension('C')->setWidth(20);
            $activeSheet->getColumnDimension('D')->setWidth(20);
            $activeSheet->getColumnDimension('E')->setWidth(20);
            $activeSheet->getColumnDimension('F')->setWidth(20);
            $activeSheet->getColumnDimension('G')->setWidth(20);
            $activeSheet->getColumnDimension('H')->setWidth(20);
            $activeSheet->getColumnDimension('I')->setWidth(20);
            $activeSheet->getColumnDimension('J')->setWidth(20);
            $activeSheet->getColumnDimension('K')->setWidth(20);
            $activeSheet->getColumnDimension('L')->setWidth(20);


            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->getStyle('A1:L'. $rowIndex)
                ->getFont()
                ->setSize(7)
                ->setName('Times New Roman');


            $activeSheet
                ->getPageSetup()
                ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                ->setFitToWidth(1)
                ->setFitToHeight(0);

            $activeSheet->getPageMargins()->setTop(0.25);
            $activeSheet->getPageMargins()->setRight(0.25);
            $activeSheet->getPageMargins()->setLeft(0.25);
            $activeSheet->getPageMargins()->setBottom(0.25);

            $activeSheet->freezePane('A1');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        }

        $activeSheet
            ->getCell('C' . $rowIndex)
            ->setValue("Информация по категории '{$incidentType->name}'  по г.Алматы в период c {$dateStart}. по {$dateEnd}г. поступившие на линию «109» ССА.")
            ->getStyle()
            ->getFont()
            ->setBold(true);

        $rowIndex += 3;
        foreach ($preparedToExport as $key => $data) {
            $activeSheet->getCell('E' . $rowIndex)->setValue($key)->getStyle()->getFont()->setBold(true);

            $activeSheet->fromArray(array_keys($data[0] ?? []), null, 'A' . ($rowIndex + 1));
            $activeSheet->fromArray($data, null, 'A' . ($rowIndex + 2));

            $activeSheet
                ->getStyle('A'.($rowIndex + 1).':I'. $activeSheet->getHighestRow())
                ->applyFromArray(Ticket101ExcelExport::HStyle);

            $activeSheet
                ->getStyle('A'.($rowIndex + 1).':I'. ($rowIndex + 1))
                ->getFont()
                ->setBold(true);

            $rowIndex = $activeSheet->getHighestRow();
            $rowIndex += 3;
        }

        $activeSheet->getColumnDimension('A')->setWidth(3);
        $activeSheet->getColumnDimension('B')->setWidth(20);
        $activeSheet->getColumnDimension('C')->setWidth(20);
        $activeSheet->getColumnDimension('D')->setWidth(20);
        $activeSheet->getColumnDimension('E')->setWidth(20);
        $activeSheet->getColumnDimension('F')->setWidth(20);
        $activeSheet->getColumnDimension('G')->setWidth(20);
        $activeSheet->getColumnDimension('H')->setWidth(20);
        $activeSheet->getColumnDimension('I')->setWidth(20);


        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->getStyle('A1:I'. $rowIndex)
            ->getFont()
            ->setSize(7)
            ->setName('Times New Roman');


        $activeSheet
            ->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        $activeSheet->getPageMargins()->setTop(0.25);
        $activeSheet->getPageMargins()->setRight(0.25);
        $activeSheet->getPageMargins()->setLeft(0.25);
        $activeSheet->getPageMargins()->setBottom(0.25);

        $activeSheet->freezePane('A1');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function getForces(Request $request)
    {
        if($request->ajax()){
            //плавно мигрируем в сторону очередей
            if($data = Cache::get('report_forces_data')) {
                return response()->json($data);
            }
            elseif ($data = ReportCache::getData('main_page_forces')) {
                return response()->json($data);
            }
        }

        $data = [];

        $report_id = FormationReport::approved()->max('id');
        $data['reports'] = FormationTechReport::where('form_id', $report_id)
            ->has('items')
            ->orderBy('dept_id')
            ->with(['items', 'department'])
            ->get();

        foreach ($data['reports'] as $report_key => $report) {
            foreach ($report->items as $item_key => $tech_item) {

                $report->items[$item_key]['departures_count'] = FireDepartmentResult::shiftRecords()
                    ->where('tech_id', $tech_item->id)
                    ->whereNotNull('out_time')
                    ->count();

                $report->items[$item_key]['real_departures_count'] = FireDepartmentResult::shiftRecords()
                    ->where('tech_id', $tech_item->id)
                    ->whereNotNull('out_time')
                    ->doesntHave('ticket.drill_type')
                    ->whereNull('ticket101_other_id')
                    ->count();

                $report->items[$item_key]['drill_departures_count'] = FireDepartmentResult::shiftRecords()
                    ->where('tech_id', $tech_item->id)
                    ->whereNotNull('out_time')
                    ->has('ticket.drill_type')
                    ->count();

                $report->items[$item_key]['other_departures_count'] = FireDepartmentResult::shiftRecords()
                    ->where('tech_id', $tech_item->id)
                    ->whereNotNull('out_time')
                    ->whereNotNull('ticket101_other_id')
                    ->count();

                $report->items[$item_key]['status'] = Ticket101::whereHas('results', function ($q) use ($tech_item){
                    $q->shiftRecords()
                        ->where('tech_id', $tech_item->id)
                        ->whereNull('ret_time')
                        ->whereNotNull('out_time');
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

            }
        }

        Cache::put('report_forces_data', $data, 3600);

        if($request->ajax()){
            return response()->json($data);
        }


        return view('reports.101.forces', $data);

    }

    public function exportForcesXls()
    {
        if ($data = Cache::get('report_forces_data')){
            $exportService = new ReportForcesExcelExport($data['reports']);
            $writer = $exportService->getXlsWriter();
            $fileName = 'Учет сил и средств (' . date('d.m.Y H-i') . ').xls';

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        }

        dd('Кеш не заполнен');
    }

    public function exportCard101ChronologyXls($cardId)
    {
        $exportService = new Ticket101ChronologyExcelExport($cardId);
        $writer = $exportService->getXlsWriter();
        $fileName = 'Хронология карточки 101 (' . date('d.m.Y H-i') . ').xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

    }

    public function exportEmergency101Xls(Request $request)
    {
        $date_begin = $request->date_begin;
        $date_end = $request->date_end;
        $result_id = $request->result_id;
        $burnt_id = $request->burnt_id;
        $city_area_id = $request->city_area_id;

        $stat = Ticket101::getDetailedStat($date_begin, $date_end, $result_id, $burnt_id, $city_area_id);

        $exportService = new Ticket101PeriodExcelExport($stat);
        $writer = $exportService->getXlsWriter();
        $fileName = 'Отчет-1.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

    }

    public function exportEmergency112Xls(Request $request)
    {
        $date_begin = $request->date_begin === 'null' ? null : $request->date_begin;
        $date_end = $request->date_end === 'null' ? null : $request->date_end;
        $result_id = $request->result_id === 'null' ? null : $request->result_id;
        $city_area_id = $request->city_area_id === 'null' ? null : $request->city_area_id;

        $stat = Card112::getDetailedStat($date_begin, $date_end, $result_id, $city_area_id);

        $exportService = new Ticket112PeriodExcelExport($stat);
        $writer = $exportService->getXlsWriter();
        $fileName = 'Отчет по карточке 112 за период.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

    }


    public function getOperationalPlan($id)
    {
        $operationalPlan = OperationalPlan::find($id);
        if($operationalPlan) {
            $html = view('pdf/operational-plan',
                $operationalPlan
            )->render();
            $this->sendHtml("Оперативный план.pdf", $html);
        }
    }

    public function getOperationalCard($id)
    {
        $operationalCard = OperationalCard::find($id);
        if($operationalCard) {
            $html = view('pdf/operational-card',
                $operationalCard
            )->render();
            $this->sendHtml("Оперативная карточка.pdf", $html);
        }
    }

    public function sendHtml($name, $html)
    {
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dompdf = new Dompdf();
        $dompdf->loadHTML($html, 'UTF-8');
        $dompdf->render();

        $dompdf->stream($name);
    }


    /**
     * @param Request $request
     * @param string $format
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Throwable
     */
    public function getDaily101Formatted(Request $request, string $format = 'word')
    {
        $data = (new Report($this->ticket101, $this->fireObject, $this->burntObject))->getReport();

        $dailyWordExport = new DailyWordExport(
            $data
        );

        // @todo PDF не работает корректно (но вроде оно и не нужно)
        $writer = $dailyWordExport->getWriter('Word2007');
        $fileName = 'Суточный отчет 101 - '.date('d-m-Y'). '.docx';
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }

    /**
     * @param Request $request
     * @param string $format
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @throws \Throwable
     */
    public function getDaily112Formatted(Request $request, string $format = 'word')
    {
        $data = (new Report112())->getReport();

        $dailyWordExport = new Daily112WordExport(
            $data
        );

        // @todo PDF не работает корректно (но вроде оно и не нужно)
        $writer = $dailyWordExport->getWriter('Word2007');
        $fileName = 'Суточный отчет 112 - '.date('d-m-Y'). '.docx';
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }

    private function createWordFileDownload(WriterInterface $writer, string $filename){
        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Description' => 'File Transfer',
            'Content-Disposition'=> 'attachment; filename="'.$filename.'"',
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Transfer-Encoding' => 'binary',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
        ]);
    }

    ///Чрезвычайные ситуации природного и техногенного характера
    public function getReport112EmergencyType(Request $request)
    {
        $dateFrom = $request->input('dateFrom', (new Carbon('01/01/2019'))->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));
        $incidentTypeId = $request->incidentTypeId;
        $tripResultId = $request->tripResultId;
        $emergency_name_id = $request->emergency_name_id;
        $cityAreaId = $request->city_area_id;

        $data['records'] = Card112::whereBetween('created_at', [$dateFrom, $dateTo])
            ->skipNullValue('city_area_id',$cityAreaId)
            ->skipNullValue('emergency_name_id',$emergency_name_id);
        $data['records101'] = Ticket101::whereBetween('created_at', [$dateFrom, $dateTo])
            ->skipNullValue('city_area_id',$cityAreaId);

        if($incidentTypeId) {
            $data['records'] = $data['records']->where('additional_incident_type_id', $incidentTypeId);
        }

        if($tripResultId) {
            $data['records101'] = $data['records101']->where('trip_result_id', $tripResultId);
        }

        $data['records'] = $data['records']
            ->whereHas('emergency_type', function ($q) {
                $q->where('name', 'ЧС');
            })
            ->with(['emergency_type','additionalAddress','additionalIncident'])
            ->get();

        $data['records'] = $data['records']->map(function ($item) {
            return [
                'created_at' => $item->created_at->format('d.m.Y H:i'),
                'detailed_address' => $item->detailed_address,
                'emergency_feature' => $item->emergency_feature,
                'dead' => $item->dead,
                'injured' => $item->injured,
                'additional_incident' => $item->additional_incident ? $item->additional_incident->name : '',
            ];
        });

        $data['records101'] = $data['records101']
            ->whereHas('emergency_type', function ($q) {
                $q->where('name', 'ЧС');
            })
            ->get();

        $data['records101'] = $data['records101']->map(function ($item) {
            return [
                'created_at' => $item->created_at->format('d.m.Y H:i'),
                'detailed_address' => $item->detailed_address ?? $item->location,
                'emergency_feature' => $item->ticket_result,
                'dead' => $item->children_death_count + $item->people_death_count,
                'injured' => $item->co2_poisoned_count + $item->ch4_poisoned_count + $item->gpt_burns_count,
                'additional_incident' => $item->trip_result ? $item->trip_result->name : '',
            ];
        });

        if($data['records101']->count()) {
            $data['records'] = $data['records101']->merge($data['records']);
        }

        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $data['incidentTypes'] = IncidentType::all();
        $data['tripResults'] = TripResult::all();
        Cache::put('report112_emergency_data', $data, 3600);

        if($request->ajax()) {
            return response()->json($data);
        }

        return view('reports.112.emergency_type', $data);
    }

    ///Случаи землетрясения
    public function getReportQuakes(Request $request)
    {
        $dateFrom = $request->input('dateFrom', (new Carbon('01/01/2019'))->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));

        $data['records'] = Quake::whereBetween('date_almaty', [$dateFrom, $dateTo])
            ->get();

        foreach ($data['records'] as $record) {
            $record->append('total_info');
        }

        if($request->ajax()) {
            return response()->json($data);
        }

        $data['footer'] = DailyReportPerson::where('report_type', 'quakes')
            ->where('type', 'footer_first')
            ->first();

        $dailyWordExport = new ReportQuakes($data);
        $writer = $dailyWordExport->getWriter('Word2007');
        $fileName = 'Случаи землетрясения в г. Алматы  - '.date('d-m-Y'). '.docx';
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }

    /// сход лавин
    public function getReportAvalanches(Request $request)
    {
        $dateFrom = $request->input('dateFrom', (new Carbon('01/01/2019'))->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));
        $avalanche_type_id = $request->input('avalancheTypeId', null);

        $data['records'] = Card112::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where(function ($q) use ($avalanche_type_id) {
                if($avalanche_type_id) {
                    $q->where('avalanche_type_id', $avalanche_type_id);
                }
                else {
                    $q->whereNotNull('avalanche_type_id');
                }
            })
            ->with(['avalanche_type'])
            ->get();

        if($request->ajax()) {
            return response()->json($data);
        }

        $dailyWordExport = new ReportAvalanches($data);
        $writer = $dailyWordExport->getWriter('Word2007');
        $fileName = 'Сход лавин - '.date('d-m-Y'). '.docx';
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }

    /// Информация происшествия на лифтах
    public function getReportElevators(Request $request)
    {
        $dateFrom = $request->input('dateFrom', (new Carbon('01/01/2019'))->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));
        $elevatorEmergencyTypeId = $request->input('elevatorEmergencyTypeId', null);
        $cityAreaId = $request->input('cityAreaId', null);

        $data['records'] = Card112::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where(function ($q) use ($elevatorEmergencyTypeId) {
                if($elevatorEmergencyTypeId) {
                    $q->where('elevator_emergency_type_id', $elevatorEmergencyTypeId);
                }
                else {
                    $q->whereNotNull('elevator_emergency_type_id');
                }
            })
            ->skipNullValue('city_area_id',$cityAreaId)
            ->with(['elevator_emergency_type','cityArea'])
            ->get();

        if($request->ajax()) {
            return response()->json($data);
        }

        $data['dateFrom'] = Carbon::parse($dateFrom)->format('d.m.Y');
        $data['dateTo'] = Carbon::parse($dateTo)->format('d.m.Y');

        $dailyWordExport = new ReportElevators($data);
        $writer = $dailyWordExport->getWriter('Word2007');
        $fileName = 'Тип происшествия на лифтах - '.date('d-m-Y'). '.docx';
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }

    /// Инфекционные заболевания
    public function getReportDisease(Request $request)
    {
        $dateFrom = $request->input('dateFrom', (new Carbon('01/01/2019'))->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));
        $typeId = $request->input('diseaseTypeId', null);

        $data['records'] = Card112::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where(function ($q) use ($typeId) {
                if($typeId) {
                    $q->where('disease_type_id', $typeId);
                }
                else {
                    $q->whereNotNull('disease_type_id');
                }
            })
            ->with(['disease_type','emergency_name'])
            ->get();


        if($request->ajax()) {
            return response()->json($data);
        }

        $data['footer'] = DailyReportPerson::where('report_type', 'diseases')
            ->where('type', 'footer_first')
            ->first();

        $data['dateFrom'] = Carbon::parse($dateFrom)->format('d.m.Y');
        $data['dateTo'] = Carbon::parse($dateTo)->format('d.m.Y');

        $dailyWordExport = new ReportDisease($data);
        $writer = $dailyWordExport->getWriter('Word2007');
        $fileName = 'Инфекционные заболевания - '.date('d-m-Y'). '.docx';
        $writer->save(public_path($fileName));

        return response()->download(public_path($fileName));
    }

    public function exportReport112Emergency($type)
    {
        if ($data = Cache::get('report112_emergency_data')) {
            $data = (new Report112Emergency($data))->getReport();

            if($type === 'docx') {

                $dailyWordExport = new Ticket112EmergencyWordExport($data);

                $writer = $dailyWordExport->getWriter('Word2007');
                $fileName = 'Чрезвычайные ситуации природного и техногенного характера  - '.date('d-m-Y'). '.docx';
                $writer->save(public_path($fileName));
            }
            elseif($type === 'xlsx') {
                $exportService = new Ticket112EmergencyExcelExport($data);
                $writer = $exportService->getXlsWriter();
                $fileName = 'Отчет по карточке 112 (ЧС) за период.xls';

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');

                $writer->save('php://output');
            }

            return response()->download(public_path($fileName));
        }
        dd('Кеш устарел, обновите страницу');
    }

    public function getReportOtherRides(Request $request)
    {
        $dateFrom = $request->input('dateFrom', now()->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));
        $ride_type_id = $request->rideTypeId;
        $fire_department_id = $request->fireDepartmentId;
        $direction = $request->direction;

        $data['records'] = Ticket101Other::whereBetween('created_at', [$dateFrom, $dateTo]);

        //берем только те записи, где есть выезды
        if($fire_department_id) {
            $data['records'] = $data['records']->whereHas('results', function ($q) use ($fire_department_id) {
                $q->where('fire_department_id', $fire_department_id)
                    ->whereNotNull('dispatch_time')
                ;
            });
        }
        else {
            $data['records'] = $data['records']->whereHas('results', function ($q) use ($fire_department_id) {
                $q->whereNotNull('dispatch_time');
            });
        }

        if($direction) {
            $data['records'] = $data['records']->where('direction', 'like', "%{$direction}%");
        }

        if($ride_type_id) {
            $data['records'] = $data['records']->where('ride_type_id', $ride_type_id);
        }

        $data['records'] = $data['records']
            ->with([
                'ride_type',
                'results',
                'results.department',
                'results.tech',
            ])
            ->get();

        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $data['rideTypes'] = RideType::all();
        $data['fireDepartments'] = FireDepartment::all();


        Cache::put('report101_other_rides', $data, 3600);

        if($request->ajax()) {
            return response()->json($data);
        }

        return view('reports.101.other_rides', $data);
    }

    public function exportReportOtherRides($type)
    {
        if ($data = Cache::get('report101_other_rides')) {
            $data = (new Report101OtherRides($data))->getReport();

            if($type === 'docx') {
            }
            elseif($type === 'xlsx') {
                $exportService = new Ticket101OtherRidesExcelExport($data);
                $writer = $exportService->getXlsWriter();
                $fileName = 'Общий свод по прочим выездам (101) за период.xls';

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');

                $writer->save('php://output');
            }

            return response()->download(public_path($fileName));
        }
        dd('Кеш устарел, обновите страницу');
    }

    public function getReportDrillRides(Request $request)
    {
        $dateFrom = $request->input('dateFrom', now()->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));
        $fire_department_id = $request->fireDepartmentId;
        $type = $request->type;
        $location = $request->location;

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

        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $data['normTypes'] = $normTypes;
        $data['fireDepartments'] = FireDepartment::all();

        $data['drill'] = Ticket101::whereBetween('created_at', [$dateFrom, $dateTo])
            ->drill();

        $data['psp'] = NormPsp::whereBetween('created_at', [$dateFrom, $dateTo]);

        if($fire_department_id) {
            $data['drill'] = $data['drill']->whereHas('results', function ($q) use ($fire_department_id) {
                $q->where('fire_department_id', $fire_department_id);
            });

            $data['psp'] = $data['psp']->where('fire_department_id', $fire_department_id);
        }

        if($type) {
            $data['drill'] = $data['drill']->whereHas('drill_type', function ($q) use ($type) {
                $q->where('name', $type);
            });
            $data['psp'] = $data['psp']->whereHas('norm_type', function ($q) use ($type) {
                $q->where('name',$type);
            });
        }

        if($location) {
            $data['drill'] = $data['drill']->where('location', "like","%$location%");
        }

        $drills = $data['drill']->get()->map(function ($q) {
           return [
               'date' => $q->created_at->format('d.m.Y H:i'),
               'fire_departments' => $q->results()->whereNotNull('dispatch_time')->get()->map(function ($q) {
                   return [
                       'name' => $q->department->title ?? null,
                   ];
               })->toArray(),
               'type' => $q->form_type_drill,
               'departments' => $q->results()->whereNotNull('dispatch_time')->get()->map(function ($q) {
                   return [
                       'name' => $q->tech->department ?? null,
                   ];
               })->toArray(),
               'name' => $q->object_name,
               'location' => $q->location,
               'time_begin' => $q->loc_time,
               'time_end' => $q->liqv_time,
               'responsible_person' => $q->responsible_person,
               'checked_pg_total' => $q->drill_checked_pg_total,
               'checked_pv_total' => $q->drill_checked_pv_total,
               'out_pg_total' => $q->drill_out_pg_total,
               'out_pv_total' => $q->drill_out_pv_total,
               'corrected_op_total' => $q->drill_corrected_op_total,
               'corrected_ok_total' => $q->drill_corrected_ok_total,
           ];
        })->toArray();

        $psp = $data['psp']->get()->map(function ($q) {
            return [
                'date' => $q->created_at->format('d.m.Y H:i'),
                'fire_departments' => [
                    ['name' => $q->fire_department->title ?? null]
                    ],
                'type' => $q->norm_type->name ?? null,
                'departments' => $q->departments->map(function($qq){
                    return ['name' => $qq->name];
                }),
                'name' => $q->norm_number->name ?? null,
                'location' => '',
                'time_begin' => $q->time_begin,
                'time_end' => $q->time_end,
                'responsible_person' => $q->responsible_person,
                'checked_pg_total' => '',
                'checked_pv_total' => '',
                'out_pg_total' => '',
                'out_pv_total' => '',
                'corrected_op_total' => '',
                'corrected_ok_total' => '',
            ];
        })->toArray();

        unset($data['psp'],$data['drill']);

        $data['records'] = array_merge($drills, $psp);

        Cache::put('report101_drill_rides', $data, 3600);

        if($request->ajax()) {
            return response()->json($data);
        }

        return view('reports.101.drill_rides', $data);
    }

    public function exportReportDrillRides($type)
    {
        if ($data = Cache::get('report101_drill_rides')) {
            $data = (new Report101DrillRides($data))->getReport();

            if($type === 'docx') {
            }
            elseif($type === 'xlsx') {
                $exportService = new Ticket101DrillRidesExcelExport($data);
                $writer = $exportService->getXlsWriter();
                $fileName = 'Общий свод по учениям и занятиям (101) за период.xls';

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');

                $writer->save('php://output');
            }

            return response()->download(public_path($fileName));
        }
        dd('Кеш устарел, обновите страницу');
    }


    public function getReportForcesResources(Request $request)
    {
        $dateFrom = $request->input('dateFrom', now()->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));
        $fire_department_id = $request->fireDepartmentId;

        $data = [];

        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        $reportData = FireDepartmentResult::
            whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotNull('dispatch_time')
            ->with([
                'tech',
                'ticket.trip_result',
                'ticket.drill_type',
                'ticket_other',
                'department',
            ]);

        if ($fire_department_id) {
            $reportData = $reportData->where('fire_department_id', $fire_department_id);
        }

        $data['reports'] = $reportData->orderBy('fire_department_id')->get();

        $data['fireDepartments'] = FireDepartment::all();

        Cache::put('report_forces_resources_data', $data, 3600);

        if($request->ajax()){
            return response()->json($data);
        }


        return view('reports.101.forcesResources', $data);
    }


    public function exportReportForcesResources(Request $request, $type)
    {
        if ($data = Cache::get('report_forces_resources_data')) {
            $data = (new Report101Resources($data))->getReport();

            if($type === 'docx') {
            }
            elseif($type === 'xlsx') {
                $exportService = new Ticket101ResourcesExcelExport($data);
                $writer = $exportService->getXlsWriter();
                $fileName = 'Учет сил и средств (101) за период.xls';

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');

                $writer->save('php://output');
            }

            return response()->download(public_path($fileName));
        }
        dd('Кеш устарел, обновите страницу');
    }

    public function getReportEmergencyRescueGu(Request $request)
    {
        $dateFrom = $request->input('dateFrom', now()->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));

        $data = [];

        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        $reportData = Ticket101::
            whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNull('drill_type_id');

        $reportDataASR = (clone $reportData)->whereHas('trip_result', function ($q) {
            $q->where('name', 'АСР');
        });

        $data['record'] = [
            'rides_count' => $reportData->count(),
            'rides_asr_count' => Ticket101::whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereNull('drill_type_id')
                ->whereHas('trip_result', function ($q) {
                    $q->where('name', 'АСР');
                })
                ->count(),
            'rides_false_count' => Ticket101::whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereNull('drill_type_id')
                ->whereHas('trip_result', function ($q) {
                    $q->where('name', 'Ложный');
                })
                ->count(),
            'total_staff_count' => Ticket101::whereBetween('created_at', [$dateFrom, $dateTo])
                    ->whereNull('drill_type_id')
                    ->whereHas('trip_result', function ($q) {
                        $q->where('name', 'АСР');
                    })
                    ->sum('total_staff_count') . ' чел.',
            'total_tech_count' => FireDepartmentResult::whereBetween('created_at', [$dateFrom, $dateTo])
                    ->whereHas('ticket', function ($q){
                        $q->whereNull('drill_type_id');
                    })
                    ->whereHas('ticket.trip_result', function ($q) {
                        $q->where('name', 'АСР');
                    })
                    ->whereNotNull('dispatch_time')
                    ->count() . ' ед.',

            'saved_vehicles' => $reportDataASR->sum('saved_vehicles'),
            'rescued_count' => $reportDataASR->sum('rescued_count'),
            'saved_children' => $reportDataASR->sum('saved_children'),
            'bodies_extracted' => $reportDataASR->sum('bodies_extracted'),
            'children_bodies_extracted' => $reportDataASR->sum('children_bodies_extracted'),
            'medical_care_provided' => $reportDataASR->sum('medical_care_provided'),
            'children_medical_care_provided' => $reportDataASR->sum('children_medical_care_provided'),
            'evac_count' => $reportDataASR->sum('evac_count'),
            'children_evacuated' => $reportDataASR->sum('children_evacuated'),
        ];

        Cache::put('emergency_rescue_gu_data', $data, 3600);

        if($request->ajax()){
            return response()->json($data);
        }


        return view('reports.101.emergency-rescue-gu', $data);
    }


    public function exportReportEmergencyRescueGu(Request $request, $type)
    {
        if ($data = Cache::get('emergency_rescue_gu_data')) {
            $data = (new Report101EmergencyRescueGu($data))->getReport();

            if($type === 'docx') {
            }
            elseif($type === 'xlsx') {
                $exportService = new Ticket101EmergencyRescueGuExcel($data);
                $writer = $exportService->getXlsWriter();
                $fileName = 'Форма ЧС-051.xls';

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');

                $writer->save('php://output');
            }

            return response()->download(public_path($fileName));
        }
        dd('Кеш устарел, обновите страницу');
    }

    public function getReportObjectClassification(Request $request)
    {
        $year = $request->input('year', now()->format('Y'));

        $data['object_classes'] = ObjectClassification::all();

        $drillTypes = DrillType::whereIn('name', ['ПТЗ','ПТУ'])->get();

        foreach ($drillTypes as $type) {

            foreach (range(1, 12) as $month) {

                $data['counts'][$type->name]['per_month'][$month] = Ticket101::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->where('drill_type_id',$type->id)
                    ->count();

                foreach ($data['object_classes'] as $object_class) {

                    $data['records'][$type->name][$object_class->name][$month] = Ticket101::whereYear('created_at', $year)
                        ->where('object_classification_id', $object_class->id)
                        ->whereMonth('created_at', $month)
                        ->where('drill_type_id',$type->id)->count();

                    $data['counts'][$type->name]['per_object'][$object_class->name] = Ticket101::whereYear('created_at', $year)
                        ->where('drill_type_id',$type->id)
                        ->where('object_classification_id', $object_class->id)
                        ->count();
                }
            }
        }

        $data['year'] = $year;

        Cache::put('report101_object_classes', $data, 3600);

        if($request->ajax()) {
            return response()->json($data);
        }

        return view('reports.101.object-classifications', $data);
    }

    public function exportReportObjectClassification($type)
    {
        if ($data = Cache::get('report101_object_classes')) {
            $data = (new Report101ObjectClass($data))->getReport();

            if($type === 'docx') {
            }
            elseif($type === 'xlsx') {
                $exportService = new Ticket101ObjectClassExcelExport($data);
                $writer = $exportService->getXlsWriter();
                $fileName = 'Классификация объектов (101) за период.xls';

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');

                $writer->save('php://output');
            }

            return response()->download(public_path($fileName));
        }
        dd('Кеш устарел, обновите страницу');
    }

    public function getReportWaterConsumption(Request $request)
    {
        $dateFrom = $request->input('dateFrom', now()->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));

        $data = [];

        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        $data['records'] = [];


        $cards = Ticket101::real()
            ->whereHas('trip_result', function ($q) {
                $q->where('name','Пожар');
            })
            ->whereHas('chronologies', function ($b) {
                $b->whereNotNull('event_info_arrived_id');
            })
            ->where(function ($qq){
                $qq->has('liquidation_method');
            })
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get()
        ;

        $tripResultIdsWeCount = [1,2,3,9];
        $tripResultIdsBoolean = [4,5];

        foreach ($cards as $card) {
            $result = [];
            $str = '';

            $result['id'] = $card->id;
            $result['liquidation_method_id'][1] = null;
            $result['liquidation_method_id'][2] = null;
            $result['liquidation_method_id'][3] = null;
            $result['liquidation_method_id'][9] = null;

            $result['liquidation_method_id'][4] = null;
            $result['liquidation_method_id'][5] = null;

            if(in_array($card->liquidation_method_id, $tripResultIdsWeCount)) {
                foreach ($card->chronologies_arrived as $chronology) {
                    $str .= $chronology->fire_department_result->department->title;
                    $str .= " ({$chronology->fire_department_result->tech->department}: {$chronology->event_info_arrived->name}: {$chronology->working_time} мин;) ";
                }
                $result['liquidation_method_id'][$card->liquidation_method_id] = $str;
            }
            elseif (in_array($card->liquidation_method_id, $tripResultIdsBoolean)) {
                $result['liquidation_method_id'][$card->liquidation_method_id] = 1;
            }
            else {
                continue;
            }

            $result['time'] = $card->liqv_time_total_minutes;

            $data['records'][] = $result;
        }

        Cache::put('report101_water_consumption', $data, 3600);

        if($request->ajax()) {
            return response()->json($data);
        }
    }

    public function exportReportWaterConsumption($type)
    {
        if ($data = Cache::get('report101_water_consumption')) {
            $data = (new Report101WaterConsumption($data))->getReport();

            if($type === 'docx') {
            }
            elseif($type === 'xlsx') {
                $exportService = new Ticket101WaterConsumptionExcelExport($data);
                $writer = $exportService->getXlsWriter();
                $fileName = 'Расход воды (101) за период.xls';

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');

                $writer->save('php://output');
            }

            return response()->download(public_path($fileName));
        }
        dd('Кеш устарел, обновите страницу');
    }

    public function daily_reports(Request $request, $type = '101')
    {
        $data = [];
        $perPage = $request->get('per_page', 10);
        $data['items'] = Analytics101::orderBy('id', 'desc')
            ->paginate($perPage);
        $data['per_page'] = $perPage;
        $data['user'] = Auth::user();

        return view("daily-reports.$type",$data);
    }
}
