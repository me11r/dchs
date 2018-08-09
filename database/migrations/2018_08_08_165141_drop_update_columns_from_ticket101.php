<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUpdateColumnsFromTicket101 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            for ($i = 1; $i < 6; $i++) {
                $table->dropColumn('update_' . $i . '_time');
                $table->dropColumn('update_' . $i . '_info');
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
        Schema::table('ticket101', function (Blueprint $table) {
            for ($i = 1; $i < 6; $i++) {
                $table->time('update_' . $i . '_time')->nullable();
                $table->text('update_' . $i . '_info')->nullable();
            }
        });
    }
}
