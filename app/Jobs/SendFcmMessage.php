<?php

namespace App\Jobs;

use App\Entities\Fcm\FcmMessage;
use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendFcmMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var FcmMessage
     */
    private $fcmMessage;

    /**
     * SendFcmMessage constructor.
     * @param FcmMessage $fcmMessage
     */
    public function __construct(FcmMessage $fcmMessage)
    {
        $this->fcmMessage = $fcmMessage;
    }


    /**
     * @param FcmService $fcmService
     */
    public function handle(FcmService $fcmService): void
    {
        $fcmService->sendMessage($this->fcmMessage);
    }
}
