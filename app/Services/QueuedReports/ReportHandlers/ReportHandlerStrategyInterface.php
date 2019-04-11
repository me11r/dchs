<?php


namespace App\Services\QueuedReports\ReportHandlers;

use App\Models\QueuedReport;

interface ReportHandlerStrategyInterface
{
    public function saveToFile(QueuedReport $queuedReport, $reportData): string ;

    public function getData(QueuedReport $queuedReport);
}