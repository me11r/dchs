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
    private $handlerFactory;

    /**
     * @var ReportsCacheManager
     */
    private $reportsCacheManager;

    /** @var QueuedReport */
    private $queuedReport;


    /**
     * QueuedReportManager constructor.
     * @param ReportHandlerFactory $handlerFactory
     * @param ReportsCacheManager $reportsCacheManager
     */
    public function __construct(ReportHandlerFactory $handlerFactory, ReportsCacheManager $reportsCacheManager)
    {
        $this->handlerFactory = $handlerFactory;
        $this->reportsCacheManager = $reportsCacheManager;
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $queuedReport = $this->getQueuedReport();
        $queuedReport->queue_status_id = QueueStatus::getBySlug(QueueStatusType::IN_PROGRESS)->id;
        $queuedReport->attempts++;
        $queuedReport->save();

        try {
            $handler = $this->handlerFactory->create($queuedReport->reportType->slug);
            $filePath = $handler->saveToFile(
                $queuedReport,
                $this->getReportData()
            );

            $queuedReport->file_path = $filePath;
            $queuedReport->queue_status_id = QueueStatus::getBySlug(QueueStatusType::ENDED)->id;
        } catch (\Exception $e) {
            $queuedReport->error_text = $e->getMessage();
            $queuedReport->queue_status_id = QueueStatus::getBySlug(QueueStatusType::ERROR)->id;
        }

        $queuedReport->save();
    }


    /**
     * @return mixed
     * @throws Exceptions\ReportHandlerNotFound
     * @throws \Exception
     */
    public function getReportData()
    {
        $queuedReport = $this->getQueuedReport();
        $handler = $this->handlerFactory->create($queuedReport->reportType->slug);
        return $this->reportsCacheManager->rememberForever($queuedReport->cache_hash_key, function() use ($handler, $queuedReport){
            return $handler->getData($queuedReport);
        });
    }

    /**
     * @return QueuedReport
     * @throws \Exception
     */
    public function getQueuedReport(): QueuedReport
    {
        if (!$this->queuedReport) {
            throw new \Exception('Report is not set');
        }
        return $this->queuedReport;
    }

    /**
     * @param QueuedReport $queuedReport
     * @return QueuedReportManager
     */
    public function setQueuedReport(QueuedReport $queuedReport): QueuedReportManager
    {
        $this->queuedReport = $queuedReport;
        return $this;
    }
}
