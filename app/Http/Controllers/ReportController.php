<?php

namespace App\Http\Controllers;

use App\AirRescueReport;
use App\CallInfo;
use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireObject;
use App\Dictionary\TripResult;
use App\FormationReport;
use App\FormationTechReport;
use App\Models\Card112\Card112;
use App\Models\EmergencySituation;
use App\Models\FireDepartmentResult;
use App\Models\FormationPersonsItem;
use App\Models\FormationTechItem;
use App\Models\IncidentType;
use App\Models\OperationalPlan;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Models\Weather;
use App\OperationalCard;
use App\Reports\Report;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\FireObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use App\Services\ReportExport\DailyWordExport;
use App\Services\ReportExport\ReportForcesExcelExport;
use App\Services\ReportExport\Ticket101ChronologyExcelExport;
use App\Services\ReportExport\Ticket101ExcelExport;
use App\Services\ReportExport\Ticket101PeriodExcelExport;
use App\Services\ReportExport\Ticket101WordExport;
use App\Services\ReportExport\Ticket112PeriodExcelExport;
use App\SirenSpeechTech;
use App\Ticket101;
use App\Ticket101ServicePlan;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
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

        //todo для теста
//        return $html;

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

    public function postReport112Emergency(Request $request)
    {
        $date_begin = $request->date_begin;
        $date_end = $request->date_end;
        $reason_id = $request->reason_id;

        $result = Card112::getDetailedStat($date_begin, $date_end, $reason_id);

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
        $dateStart = Carbon::parse($request->get('date_start'))->format('Y-m-d');
        $dateEnd = Carbon::parse($request->get('date_end'))->format('Y-m-d');

        $fileName = 'Отчет:'
            . $dateStart
            . '_'
            . $dateEnd
            . '.xls';

        $cards = (new Card112())
            ->where('incident_type_id', '=', $request->get('incident_type_id'))
            ->with(['cityArea'])
            ->get();

        $incidentType = IncidentType::find($request->get('incident_type_id'));

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

        $rowIndex = 1;
        $activeSheet = $spreadsheet->getActiveSheet();
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
                ->getStyle('A'.($rowIndex + 1).':H'. $activeSheet->getHighestRow())
                ->applyFromArray(Ticket101ExcelExport::HStyle);

            $activeSheet
                ->getStyle('A'.($rowIndex + 1).':H'. ($rowIndex + 1))
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


        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->getStyle('A1:H'. $rowIndex)
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
        $fileName = 'Отчет по карточке 101 за период.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

    }

    public function exportEmergency112Xls(Request $request)
    {
        $date_begin = $request->date_begin;
        $date_end = $request->date_end;
        $result_id = $request->result_id;

        $stat = Card112::getDetailedStat($date_begin, $date_end, $result_id);

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

        $view = view('reports.export.word.daily-report-112', $data)->render();
        $word = new PhpWord();
        $section = $word->addSection();
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $view, false, false);
        $file = 'Суточный отчет 112 - '.date('d-m-Y'). '.docx';
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($word);
        return $this->createWordFileDownload($writer, $file);

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
}
