<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101DropPhs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $columns = [];
            for ($i = 1; $i < 18; $i++) {
                $columns[] = 'ph_' . $i . '_ot';
                $columns[] = 'ph_' . $i . '_out_time';
                $columns[] = 'ph_' . $i . '_arrive_time';
                $columns[] = 'ph_' . $i . '_loc_time';
                $columns[] = 'ph_' . $i . '_liqv_time';
                $columns[] = 'ph_' . $i . '_ret_time';
                $columns[] = 'ph_' . $i . '_dispatched';
                $columns[] = 'ph_' . $i . '_dispatch_id';

            }

            $table->dropColumn($columns);
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
            for ($i = 1; $i < 18; $i++) {
                $table->text('ph_' . $i . '_ot')->after('id')->nullable();
                $table->time('ph_' . $i . '_out_time')->after('id')->nullable();
                $table->time('ph_' . $i . '_arrive_time')->after('id')->nullable();
                $table->time('ph_' . $i . '_loc_time')->after('id')->nullable();
                $table->time('ph_' . $i . '_liqv_time')->after('id')->nullable();
                $table->time('ph_' . $i . '_ret_time')->after('id')->nullable();
                $table->boolean('ph_' . $i . '_dispatched')->after('id')->default(false);
                $table->integer('ph_' . $i . '_dispatch_id')->after('id')->nullable();
            }
        });
    }
}
