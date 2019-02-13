<?php

namespace App\Http\Controllers;

use App\Dictionary\CityArea;
use App\Dictionary\TripResult;
use App\EmergencyName;
use App\Models\Card112\Card112;
use App\Models\IncidentType;
use App\Reports\Report112;
use App\Services\ReportExport\Daily112WordExport;
use App\Ticket101;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Analytics112Controller extends Controller
{
    public function download($date, $type = 'word')
    {
        $data = (new Report112($date))->getReport();

        if($type === 'word') {
            $dailyWordExport = new Daily112WordExport(
                $data
            );
            $writer = $dailyWordExport->getWriter('Word2007');
            $fileName = 'Суточный отчет 112 - '.date('d-m-Y', strtotime($date)). '.docx';
            $writer->save(public_path($fileName));

            return response()->download(public_path($fileName));
        }

        elseif($type === 'pdf') {

            $resultString = 'ЧС – ' . $data['cards112']->count().', ';

            foreach (TripResult::all() as $reason) {

                $cnt = $data['cards101']->filter(function ($event) use ($reason) {
                    return $event->trip_result_id == $reason->id;
                });

                if($cnt->count()){
                    $resultString .= "{$reason->name} – " . $cnt->count().', ';
                }
            }

            $resultString .= 'погиб – ' . $data['dead_count'].', ';
            $resultString .= 'спасено – ' . $data['saved_count'].', ';
            $resultString .= 'эвакуировано – ' . $data['evacuated_count'].', ';
            $resultString .= 'отравление природным/ угарным газом – ' . $data['poisoningCount'].', ';
            $resultString .= 'пострадавший. – ' . $data['hurt_count'].'.';

            $data['emergency_string'] = $resultString;

            $html = view('pdf/operational-report112', $data)->render();

            $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
            $date = date('d-m-Y');
            $file_name = "Суточный отчет - $date.pdf";

            $dompdf = new Dompdf();
            $dompdf->loadHTML($html, 'UTF-8');
            $dompdf->render();

            $dompdf->stream($file_name);
        }

    }

    public function index(Request $request)
    {
        $data = [];
        $data['incident_types'] = IncidentType::orderBy('name')->get();
        $data['user'] = Auth::user();
        $data['branches'] = (new IncidentType)
            ->whereIn('name', ['Подтопления', 'Падение веток и деревьев'])
            ->orderBy('name')
            ->get();

        $data['city_areas'] = CityArea::all();
        $data['emergency_names'] = EmergencyName::all();

        $data['tripResults'] = TripResult::all();

        $dateFrom = $request->input('dateFrom', (new Carbon('01/01/2019'))->format('Y-m-d'));
        $dateTo = $request->input('dateTo', now()->format('Y-m-d'));
        $incidentTypeId = $request->incidentTypeId;
        $tripResultId = $request->tripResultId;

        $data['records'] = Card112::whereBetween('created_at', [$dateFrom, $dateTo]);
        $data['records101'] = Ticket101::whereBetween('created_at', [$dateFrom, $dateTo]);

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

        return view('reports.112.index',$data);
    }
}
