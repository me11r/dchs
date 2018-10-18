<?php

namespace App\Jobs;

use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendFcmMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private $tokens;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $body;

    /**
     * SendFcmMessages constructor.
     * @param array $tokens
     * @param string $title
     * @param string $body
     */
    public function __construct(array $tokens, string $title, string $body)
    {
        $this->tokens = $tokens;
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * @param FcmService $fcmService
     */
    public function handle(FcmService $fcmService): void
    {
        $fcmService->sendToMany(
            $this->tokens,
            $this->title,
            $this->body
        );
    }
}
