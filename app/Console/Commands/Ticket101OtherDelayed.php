<?php

namespace App\Console\Commands;

use App\PopupNotification;
use App\Ticket101Other;
use Illuminate\Console\Command;

class Ticket101OtherDelayed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:ticket101other';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'проверка отложенных вызывов (101 прочие)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = now()->addMinutes(5)->format('Y-m-d H:i');

        $ticket101Others = Ticket101Other::where('delayed_at', '<=', $now)
            ->whereNotNull('created_by')
            ->get();

        foreach ($ticket101Others as $ticket) {

            /*отпаравка сообщения тому пользователю, который создал карточку*/
            $user = $ticket->created_by_user;

            PopupNotification::firstOrCreate([
                'sender_id' => $user->id,
                'receiver_id' => $user->id,
                'message' => "Необходимо подтвердить высылку карточки №{$ticket->id}",
            ],[
                'sender_id' => $user->id,
                'receiver_id' => $user->id,
                'message' => "Необходимо подтвердить высылку карточки №{$ticket->id}",
                'url' => "/card101-other-rides/{$ticket->id}/edit",
                'is_viewed' => false,
            ]);

        }
    }
}
