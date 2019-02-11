<?php

namespace App\Http\Controllers;

use App\Dictionary\TripResult;
use App\Reports\Report112;
use App\Services\ReportExport\Daily112WordExport;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

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
}
