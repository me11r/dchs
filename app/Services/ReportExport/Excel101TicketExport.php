<?php


namespace App\Services\ReportExport;


class Excel101TicketExport
{
    private $writer;

    public function __construct($date )
    {
        $this->writer = $this->prepareWriter();
    }

    private function prepareWriter()
    {
        
    }
}
