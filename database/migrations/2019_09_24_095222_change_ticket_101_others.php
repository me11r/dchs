<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101Others extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('ticket101_others', function($table) {
                 $table->string('move', 255);
                 $table->string('area_id', 255);
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
           Schema::table('ticket101_others', function($table) {
           $table->dropColumn('move');
           $table->dropColumn('area_id');
        });
    }
}
