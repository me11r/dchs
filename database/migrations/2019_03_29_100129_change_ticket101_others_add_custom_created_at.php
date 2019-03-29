<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101OthersAddCustomCreatedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_others', function (Blueprint $table) {
            $table->timestamp('custom_created_at')
                ->index()
                ->after('created_at')
                ->nullable();
        });

        $cards = \App\Ticket101Other::all();

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
        Schema::table('ticket101_others', function (Blueprint $table) {
            $table->dropIndex(['custom_created_at']);
            $table->dropColumn(['custom_created_at']);
        });
    }
}
