<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101AddCustomCreatedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('ticket101')) {
            Schema::table('ticket101', function (Blueprint $table) {
                $table->timestamp('custom_created_at')
                    ->after('created_at')
                    ->nullable()
                    ->index();
            });
        }

        $cards = \App\Ticket101::all();

        foreach ($cards as $card) {
            $card->custom_created_at = $card->created_at;
            $card->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->dropIndex(['custom_created_at']);
            $table->dropColumn(['custom_created_at']);
        });
    }
}
