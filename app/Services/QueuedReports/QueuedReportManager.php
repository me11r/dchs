<?php

namespace App\Services\QueuedReports;

use App\Enums\QueueStatusType;
use App\Models\QueuedReport;
use App\Models\QueueStatus;

class QueuedReportManager
{
    /**
     * @var ReportHandlerFactory
     */
    protected $handlerFactory;

    /**
     * ImporterManager constructor.
     * @param ReportHandlerFactory $handlerFactory
     */
    public function __construct(ReportHandlerFactory $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
    }

    public function handle(int $queuedReportId): void
    {
        /** @var QueuedReport $queuedReport */
        $queuedReport = QueuedReport::where('id', '=', $queuedReportId)->firstOrFail();
        $queuedReport->queue_status_id = QueueStatus::getBySlug(QueueStatusType::IN_PROGRESS)->id;
        $queuedReport->attempts++;
        $queuedReport->save();

        try {
            $handler = $this->handlerFactory->create($queuedReport->reportType->slug);
            $filePath = $handler->saveToFile($queuedReport);

            $queuedReport->file_path = $filePath;
            $queuedReport->queue_status_id = QueueStatus::getBySlug(QueueStatusType::ENDED)->id;
        } catch (\Exception $e) {
            $queuedReport->error_text = $e->getMessage();
            $queuedReport->queue_status_id = QueueStatus::getBySlug(QueueStatusType::ERROR)->id;
        }

        $queuedReport->save();
    }
}
