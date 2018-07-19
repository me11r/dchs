<?php

namespace App\Http\Controllers;


use App\Reports\Report;
use App\Repositories\Contracts\Tiket101Interface;
use Dompdf\Dompdf;

class ReportController extends AuthorizedController
{
    protected $tiket101;

    public function __construct(Tiket101Interface $tiket101)
    {
        $this->tiket101 = $tiket101;
        parent::__construct();
    }

    public function getDaily()
    {
        $html = view('pdf/daily-report',
            (new Report($this->tiket101))->getReport()
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