<?php

namespace App\Http\Controllers;


use App\Models\FormationPersonsItem;
use App\Models\FormationTechItem;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Reports\Report;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\FireObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

    public function getReport101()
    {
        if($data = Cache::get('report101_data')){
            $html = view('pdf/formation-report', $data);
            $html_test = view('pdf/formation-report-test', $data);

            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

            $cellRowSpan = ['vMerge' => 'restart'];
            $cellRowContinue = ['vMerge' => 'continue'];
            $cellColSpan = ['gridSpan' => 2];


            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection(['orientation' => 'landscape',]);
            $section->getStyle()->setBreakType('continuous');
//            $header = $section->addHeader();
            $table = $section->addTable();

            $table->addRow(-0.5, array('exactHeight' => -5));

            $table->addCell(700, $cellRowSpan)->addText('ПЧ');
            $table->addCell(700, $cellRowSpan)->addText('В карауле по списку л/с');

            $table->addCell(700, ['gridSpan' => 6])->addText('На лицо личного состава');
            $table->addCell(700, ['gridSpan' => 6])->addText('Отсутствуют');
            $table->addCell(700, $cellRowSpan)->addText('ГДЗС');
            $table->addCell(700, $cellRowSpan)->addText('Аппараты');
            $table->addCell(700, $cellRowSpan)->addText('Мотопомпы');
            $table->addCell(700, ['gridSpan' => 6])->addText('Пожарная техника');

            $table->addRow();

            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);

            $table->addCell(700, $cellRowSpan)->addText('всего');
            $table->addCell(700, $cellRowSpan)->addText('нач.кар');
            $table->addCell(700, $cellRowSpan)->addText('ком.отд');
            $table->addCell(700, $cellRowSpan)->addText('Шоферы');
            $table->addCell(700, $cellRowSpan)->addText('Ряд.состав');
            $table->addCell(700, $cellRowSpan)->addText('Ряд.Диспетчеров');
            $table->addCell(700, $cellRowSpan)->addText('Отпуск');
            $table->addCell(700, $cellRowSpan)->addText('Учебный');
            $table->addCell(700, $cellRowSpan)->addText('Декрет');
            $table->addCell(700, $cellRowSpan)->addText('Больные');
            $table->addCell(700, $cellRowSpan)->addText('Командировка');
            $table->addCell(700, $cellRowSpan)->addText('Др.причины');

            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);

            $table->addCell(700, ['gridSpan' => 2])->addText('В боевом расчёте');
            $table->addCell(700, ['gridSpan' => 2])->addText('В резерве');
            $table->addCell(700, ['gridSpan' => 2])->addText('На ремонте');

            $table->addRow();

            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);
            $table->addCell(700, $cellRowContinue);


            $table->addCell(500, $cellRowSpan)->addText('Тип осн. пожарного а/м');
            $table->addCell(500, $cellRowSpan)->addText('Марка');
            $table->addCell(500, $cellRowSpan)->addText('Тип осн. а/м');
            $table->addCell(500, $cellRowSpan)->addText('Марка');
            $table->addCell(500, $cellRowSpan)->addText('Тип осн. а/м');
            $table->addCell(500, $cellRowSpan)->addText('Марка');

            $table->addRow(-0.5, array('exactHeight' => -5));

            foreach (range(1,23) as $item) {
                $table->addCell(null, $cellRowContinue);
            }

            $table->addRow(-0.5, array('exactHeight' => -5));


            foreach (range(1,23) as $item) {
                $table->addCell(700)->addText(str_random(8));
            }

            $table->addRow(-0.5, array('exactHeight' => -5));
            dd($data);


            foreach ($data['departments'] as $dept){

                $table->addCell(700)->addText($dept->title);

                foreach ($data['people_fields'] as $ppl) {
                    $table->addCell(700)->addText($data['people'][$dept->id][$ppl] ?? '-');
                }

                foreach ($data['tech_fields'] as $tch) {
                    $table->addCell(700)->addText($data['tech'][$dept->id][$tch] ?? '-');
                }

                $tech_action_name = '';
                $tech_action_base = '';
                $tech_reserve_name = '';
                $tech_reserve_base = '';
                $tech_repair_name = '';
                $tech_repair_base = '';

                foreach ($dept->tech_action as $action) {
                    $tech_action_name .= $action->vehicle->name ?? '-';
                }

                $table->addCell(700)->addText($tech_action_name ?? '-');


                foreach ($dept->tech_action as $action) {
                    $tech_action_base .= $action->vehicle->base ?? '-';
                }

                $table->addCell(700)->addText($tech_action_base ?? '-');

                foreach ($dept->tech_reserve as $tech_reserve) {
                    $tech_reserve_name .= $tech_reserve->vehicle->name ?? '-';
                }

                $table->addCell(700)->addText($tech_reserve_name ?? '-');

                foreach ($dept->tech_reserve as $tech_reserve) {
                    $tech_reserve_base .= $tech_reserve->vehicle->base ?? '-';
                }

                $table->addCell(700)->addText($tech_reserve_base ?? '-');

                foreach ($dept->tech_repair as $tech_repair) {
                    $tech_repair_name .= $tech_repair->vehicle->name ?? '-';
                }

                $table->addCell(700)->addText($tech_repair_name ?? '-');

                foreach ($dept->tech_repair as $tech_repair) {
                    $tech_repair_base .= $tech_repair->vehicle->bas ?? '-';
                }

                $table->addCell(700)->addText($tech_repair_base ?? '-');

                $table->addRow();

            }

//            $table->addRow();

            $table->addCell(700)->addText('Итого');

            foreach (range(1,22) as $item) {
                $table->addCell(700)->addText(str_random(8));
            }
//            foreach ($data['people_fields'] as $ppl){
//                $table->addCell(700)->addText($data['sumArray']['people'][$ppl] ?? 0);
//            }
//
//            foreach ($data['tech_fields'] as $tch){
//                if($tch == 'field_4'){
//                    $table->addCell(700)->addText('-');
//                }
//                else{
//                    $table->addCell(700)->addText($data['sumArray']['tech'][$tch] ?? 0);
//                }
//            }
//
//            $table->addCell(700)->addText($data['tech_items_count']['tech_action'] ?? 0);
//            $table->addCell(700)->addText($data['tech_items_count']['tech_action'] ?? 0);
//            $table->addCell(700)->addText($data['tech_reserve']['tech_action'] ?? 0);
//            $table->addCell(700)->addText($data['tech_reserve']['tech_reserve'] ?? 0);
//            $table->addCell(700)->addText($data['tech_reserve']['tech_repair'] ?? 0);
//            $table->addCell(700)->addText($data['tech_reserve']['tech_repair'] ?? 0);





//            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html_test, false, false);
            $phpWord->save(public_path('123.docx'));
            return response()->download('123.docx');

//            $html2pdf = new Html2Pdf('L');
//            $html2pdf->writeHTML($html);
//            $html2pdf->output('report101.pdf');
        }
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
}