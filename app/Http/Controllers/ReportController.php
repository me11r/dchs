<?php

namespace App\Http\Controllers;


use App\Services\Report;
use Dompdf\Dompdf;

class ReportController extends AuthorizedController
{

    public function getDaily()
    {
        $html = view('pdf/daily-report',
            (new Report())->getReport()
        )->render();

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $date = date('d-m-Y');
        $file_name = "Суточный отчет - $date.pdf";

        $dompdf = new Dompdf();
        $dompdf->loadHTML($html, 'UTF-8');
        $dompdf->render();

        $dompdf->stream($file_name);
    }
}