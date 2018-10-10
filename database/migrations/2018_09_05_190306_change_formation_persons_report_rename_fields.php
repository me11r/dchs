<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationPersonsReportRenameFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_persons_report', function (Blueprint $table) {
            $table->renameColumn('field_0', 'total');
            $table->renameColumn('field_2_0', 'active');
            $table->renameColumn('field_2_1', 'head_guards');
            $table->renameColumn('field_2_2', 'commander_squads');
            $table->renameColumn('field_2_3', 'drivers');
            $table->renameColumn('field_2_4', 'privates');
            $table->renameColumn('field_2_5', 'dispatchers');
            $table->renameColumn('field_3_0', 'vacation');
            $table->renameColumn('field_3_1', 'study');
            $table->renameColumn('field_3_2', 'maternity');
            $table->renameColumn('field_3_3', 'sick');
            $table->renameColumn('field_3_4', 'business_trip');
            $table->renameColumn('field_3_5', 'other');
            $table->renameColumn('field_1', 'gas_smoke_protection_service');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_persons_report', function (Blueprint $table) {
            $table->renameColumn('total', 'field_0');
            $table->renameColumn('active', 'field_2_0');
            $table->renameColumn('head_guards', 'field_2_1');
            $table->renameColumn('commander_squads', 'field_2_2');
            $table->renameColumn('drivers', 'field_2_3');
            $table->renameColumn('privates', 'field_2_4');
            $table->renameColumn('dispatchers', 'field_2_5');
            $table->renameColumn('vacation', 'field_3_0');
            $table->renameColumn('study', 'field_3_1');
            $table->renameColumn('maternity', 'field_3_2');
            $table->renameColumn('sick', 'field_3_3');
            $table->renameColumn('business_trip', 'field_3_4');
            $table->renameColumn('other', 'field_3_5');
            $table->renameColumn('gas_smoke_protection_service', 'field_1');
        });
    }
}
