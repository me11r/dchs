<?php

namespace App\Services\QueuedReports;

use App\Enums\QueueStatusType;
use App\Jobs\HandleQueuedReport;
use App\Models\QueuedReport;
use App\Models\QueueStatus;
use App\Models\ReportType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class QueuedReportsService
{
    public function registerNewReport(
        Carbon $dateStart,
        Carbon $dateEnd,
        string $reportType,
        array $reportData): QueuedReport
    {
        $queuedReport = (new QueuedReport())->fill([
            'report_type_id' => ReportType::getBySlug($reportType)->id,
            'queue_status_id' => QueueStatus::getBySlug(QueueStatusType::CREATED)->id,
            'date_start' => $dateStart->format('Y-m-d H:i:s'),
            'date_end' => $dateEnd->format('Y-m-d H:i:s'),
            'report_data' => $reportData,
            'attempts' => 0,
            'error_text' => '',
            'user_id' => Auth::user()->id
        ]);

        $queuedReport->save();

        return $queuedReport;
    }

    public function changeStatus(int $queuedReportId, string $statusSlug): bool
    {
        /** @var QueuedReport $queuedReport */
        $queuedReport = QueuedReport::find($queuedReportId);
        $queuedReport->queue_status_id = QueueStatus::getBySlug($statusSlug)->id;
        if ($statusSlug === QueueStatusType::QUEUED) {
            $queuedReport->attempts = 0;
        }
        return $queuedReport->save();
    }

    public function sendToQueue(int $queuedReportId)
    {
        $result = $this->changeStatus($queuedReportId, QueueStatusType::QUEUED);
        dispatch(new HandleQueuedReport($queuedReportId));
        return $result;
    }
}
