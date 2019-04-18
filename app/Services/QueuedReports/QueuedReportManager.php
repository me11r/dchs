<?php

namespace App\Services\QueuedReports;

use App\Enums\QueueStatusType;
use App\Models\QueuedReport;
use App\Models\QueueStatus;
use App\Services\NotificationService;

class QueuedReportManager
{
    /**
     * @var ReportHandlerFactory
     */
    private $handlerFactory;

    /**
     * @var ReportsCacheService
     */
    private $reportsCacheService;

    /** @var QueuedReport */
    private $queuedReport;

    /**
     * @var NotificationService
     */
    private $notificationService;


    private const ATTEMPTS = 1;

    /**
     * QueuedReportManager constructor.
     * @param ReportHandlerFactory $handlerFactory
     * @param ReportsCacheService $reportsCacheService
     * @param NotificationService $notificationService
     */
    public function __construct(ReportHandlerFactory $handlerFactory, ReportsCacheService $reportsCacheService, NotificationService $notificationService)
    {
        $this->handlerFactory = $handlerFactory;
        $this->reportsCacheService = $reportsCacheService;
        $this->notificationService = $notificationService;
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $queuedReport = $this->getQueuedReport();

        if ($queuedReport->attempts >= self::ATTEMPTS){
            $queuedReport->error_text = 'При генерации отчета произошла непредвиденная ошибка.';
            $queuedReport->queue_status_id = QueueStatus::getBySlug(QueueStatusType::ERROR)->id;
            $this->makeNotifications($this->getErrorMessage());
        } else {
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
                $queuedReport->save();

                $this->makeNotifications($this->getSuccessMessage());
            } catch (\Exception $e) {
                $queuedReport->error_text = $e->getMessage();
                $queuedReport->queue_status_id = QueueStatus::getBySlug(QueueStatusType::ERROR)->id;
                $queuedReport->save();

                $this->makeNotifications($this->getErrorMessage());
            }
        }
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
        return $this->reportsCacheService->rememberForever($queuedReport->cache_hash_key, function() use ($handler, $queuedReport){
            return $handler->getData($queuedReport);
        });
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getSuccessMessage()
    {
        $queuedReport = $this->getQueuedReport();
        return 'Генерация отчета "' . $queuedReport->reportType->name . '" успешно заверешена.';
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getErrorMessage()
    {
        $queuedReport = $this->getQueuedReport();
        return 'При генерации отчета "' . $queuedReport->reportType->name . '" произошла ошибка.';
    }

    /**
     * @param string $message
     * @throws \Exception
     */
    private function makeNotifications(string $message)
    {
        $queuedReport = $this->getQueuedReport();
        $notificationUser = $this->notificationService->getNotificationUser();

        $this->notificationService->sendMessage(
            $notificationUser->id,
            $queuedReport->user_id,
            $message
        );

        $this->notificationService->sendPopupMessage(
            $notificationUser->id,
            $queuedReport->user_id,
            $message,
            '/reports/queued-reports'
        );
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
