<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTicket101Columns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codeIds = (new \App\Models\NotificationService())
            ->get()
            ->pluck('id', 'code');

        (new App\Ticket101())->get()->each(function ($ticket) use ($codeIds) {
            foreach ($codeIds as $code => $id) {
                (new \App\Models\Ticket101\Ticket101Notification())
                    ->fill([
                        'notification_service_id' => $id,
                        'ticket101_id' => $ticket->id,
                        'name' => $ticket->{'name_' . $code . '_recv'},
                        'message_time' => $ticket->{'call_' . $code . '_time'},
                        'arrive_time' => $ticket->{'arrival_' . $code},
                        'checked' => (bool)$ticket->{'name_' . $code . '_recv'}
                    ])
                    ->save();
            }
        });

        Schema::table('ticket101', function (Blueprint $table) use ($codeIds) {
            foreach ($codeIds as $code => $id){
                $table->dropColumn('name_' . $code . '_recv');
                $table->dropColumn('call_' . $code . '_time');
                $table->dropColumn('arrival_' . $code);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
