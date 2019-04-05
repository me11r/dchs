<?php

namespace App\Jobs;

use App\Services\QueuedReports\QueuedReportManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class HandleQueuedReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $queuedReportId;

    public function __construct(int $queuedReportId)
    {
        $this->queuedReportId = $queuedReportId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(QueuedReportManager $reportManager)
    {
        $reportManager->handle($this->queuedReportId);
    }
}
