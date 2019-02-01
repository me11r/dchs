<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDrillAdditionalFieldsToTicket101 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->string('drill_type_total')->nullable();
            $table->string('drill_name_total')->nullable();
            $table->string('drill_address_total')->nullable();
            $table->string('drill_checked_pg_total')->nullable();
            $table->string('drill_checked_pv_total')->nullable();
            $table->string('drill_out_pg_total')->nullable();
            $table->string('drill_out_pv_total')->nullable();
            $table->string('drill_corrected_op_total')->nullable();
            $table->string('drill_corrected_ok_total')->nullable();
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
            $table->dropColumn([
                'drill_type_total', 'drill_name_total',
                'drill_address_total', 'drill_checked_pg_total',
                'drill_checked_pg_total', 'drill_checked_pv_total',
                'drill_out_pg_total', 'drill_out_pv_total',
                'drill_corrected_op_total', 'drill_corrected_ok_total']);
        });
    }
}
