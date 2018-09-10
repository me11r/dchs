<?php

namespace App\Http\Controllers;


use App\Reports\Report;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\FireObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use Dompdf\Dompdf;
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
}