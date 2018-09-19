<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101MakeObjectNameNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->string('object_name')->nullable(true)->change();
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
            $table->string('object_name')->nullable(false)->change();
        });
    }
}
