<?php

namespace App\Services\QueuedReports\ReportHandlers;

use App\Enums\ReportType;
use App\Models\QueuedReport;
use App\Services\ReportExport\Ticket101PeriodExcelExport;
use App\Ticket101;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class AnalyticsSpiasrStrategy implements ReportHandlerStrategyInterface
{

    /**
     * @param QueuedReport $queuedReport
     * @param $reportData
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function saveToFile(QueuedReport $queuedReport, $reportData): string
    {
        $exportService = new Ticket101PeriodExcelExport($reportData);
        $writer = $exportService->getXlsWriter();

        $fileName = $this->getFileName($queuedReport);
        $directory = storage_path('reports' . DIRECTORY_SEPARATOR . ReportType::ANALYTICS_SPIASR . DIRECTORY_SEPARATOR);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filePath = $directory . $fileName;

        $writer->save($filePath);

        return $filePath;
    }

    /**
     * @param QueuedReport $queuedReport
     * @return mixed
     */
    public function getData(QueuedReport $queuedReport)
    {
        $data = $queuedReport->report_data;

        $date_begin = Arr::get($data, 'date_begin');
        $date_end = Arr::get($data, 'date_end');
        $result_id = Arr::get($data, 'result_id');
        $burnt_id = Arr::get($data, 'burnt_id');
        $city_area_id = Arr::get($data, 'city_area_id');

        return Ticket101::getDetailedStat($date_begin, $date_end, $result_id, $burnt_id, $city_area_id);
    }

    /**
     * @param QueuedReport $queuedReport
     * @return string
     */
    private function getFileName(QueuedReport $queuedReport)
    {
        return 'Отчет-1_' .
            Carbon::parse($queuedReport->date_start)->format('d.m.Y') .
            '_' .
            Carbon::parse($queuedReport->date_end)->format('d.m.Y') .
            '_' .
            $queuedReport->id .
            '.xls';
    }

}
