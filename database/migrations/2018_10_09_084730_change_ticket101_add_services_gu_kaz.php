<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101AddServicesGuKaz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->text('name_gu_kaz_recv')->nullable()->after('name_smk_recv');
            $table->text('name_roso_recv')->nullable()->after('name_smk_recv');
            $table->text('name_kaz_aviaserice_recv')->nullable()->after('name_smk_recv');
            $table->text('name_ao_ort_recv')->nullable()->after('name_smk_recv');

            $table->text('call_gu_kaz_recv')->nullable()->after('name_smk_recv');
            $table->text('call_roso_recv')->nullable()->after('name_smk_recv');
            $table->text('call_kaz_aviaserice_recv')->nullable()->after('name_smk_recv');
            $table->text('call_ao_ort_recv')->nullable()->after('name_smk_recv');

            $table->text('arrival_gu_kaz_recv')->nullable()->after('name_smk_recv');
            $table->text('arrival_roso_recv')->nullable()->after('name_smk_recv');
            $table->text('arrival_kaz_aviaserice_recv')->nullable()->after('name_smk_recv');
            $table->text('arrival_ao_ort_recv')->nullable()->after('name_smk_recv');
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
                'name_gu_kaz_recv',
                'name_roso_recv',
                'name_kaz_aviaserice_recv',
                'name_ao_ort_recv',

                'call_gu_kaz_recv',
                'call_roso_recv',
                'call_kaz_aviaserice_recv',
                'call_ao_ort_recv',

                'arrival_gu_kaz_recv',
                'arrival_roso_recv',
                'arrival_kaz_aviaserice_recv',
                'arrival_ao_ort_recv',
            ]);
        });
    }
}
