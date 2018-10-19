<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101ServicePlansAddServiceTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_service_plans', function (Blueprint $table) {
            $table->unsignedInteger('service_type_id')->after('id')->nullable();
            $table->foreign('service_type_id')
                ->references('id')
                ->on('service_types')
                ->onDelete('cascade');

            $table->dropColumn(['department']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101_service_plans', function (Blueprint $table) {
            $table->string('department')->after('id');
            $table->dropForeign(['service_type_id']);
            $table->dropColumn(['service_type_id']);
        });
    }
}
