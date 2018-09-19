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

            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

            $html2pdf = new Html2Pdf('L');
            $html2pdf->writeHTML($html);
            $html2pdf->output('report101.pdf');
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