<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFireDepartmentResultsAddTicket101OtherId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->unsignedInteger('ticket101_other_id')->after('id')->nullable();
            $table->unsignedInteger('ticket101_id')->nullable(true)->change();

            $table->foreign('ticket101_other_id')
                ->references('id')
                ->on('ticket101_others')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->unsignedInteger('ticket101_id')->nullable(false)->change();
            $table->dropForeign(['ticket101_other_id']);
            $table->dropColumn(['ticket101_other_id']);
        });
    }
}
