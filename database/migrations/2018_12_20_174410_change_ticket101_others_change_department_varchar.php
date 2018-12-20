<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101OthersChangeDepartmentVarchar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_others', function (Blueprint $table) {
            $table->string('department')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101_others', function (Blueprint $table) {
            $table->dropColumn(['department']);
        });

        Schema::table('ticket101_others', function (Blueprint $table) {
            $table->integer('department')->after('id')->nullable(true);
        });
    }
}
