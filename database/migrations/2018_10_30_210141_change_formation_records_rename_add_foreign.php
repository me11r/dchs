<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationRecordsRenameAddForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_records', function (Blueprint $table) {

            /*$table->dropIndex(['organisation']);
            $table->dropColumn(['organisation']);

            $table->unsignedInteger('organisation_id')->nullable()->after('id');
            $table->foreign('organisation_id')
                ->references('id')
                ->on('service_types')
                ->onDelete('cascade');*/

            $table->renameColumn('field_1_0_0', 'head')->change();
            $table->renameColumn('field_2_0_0', 'staff_total')->change();
            $table->renameColumn('field_2_1_0', 'staff_action')->change();
            $table->renameColumn('field_2_2_0', 'staff_duty_shift')->change();
            $table->renameColumn('field_3_0_0', 'tech_main_action')->change();
            $table->renameColumn('field_3_0_1', 'tech_main_reserve')->change();
            $table->renameColumn('field_3_1_0', 'tech_special_action')->change();
            $table->renameColumn('field_3_1_1', 'tech_special_reserve')->change();
            $table->renameColumn('field_3_2_0', 'tech_additional_action')->change();
            $table->renameColumn('field_3_2_1', 'tech_additional_reserve')->change();
            $table->renameColumn('field_3_3_0', 'tech_other_action')->change();
            $table->renameColumn('field_3_3_1', 'tech_other_reserve')->change();
            $table->renameColumn('field_4_0_0', 'gsm_gasoline')->change();
            $table->renameColumn('field_4_1_0', 'gsm_diesel')->change();
            $table->renameColumn('field_5_0_0', 'radio_stations')->change();
            $table->renameColumn('field_6_0_0', 'personal_respiratory_protection')->change();
            $table->renameColumn('field_7_0_0', 'personal_protection')->change();
            $table->renameColumn('field_8_0_0', 'other_protection')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_records', function (Blueprint $table) {
            /*$table->string('organisation', 50)->after('id')->index();
            $table->dropForeign(['organisation_id']);
            $table->dropColumn(['organisation_id']);*/

            $table->renameColumn('head', 'field_1_0_0')->change();
            $table->renameColumn('staff_total', 'field_2_0_0')->change();
            $table->renameColumn('staff_action', 'field_2_1_0')->change();
            $table->renameColumn('staff_duty_shift', 'field_2_2_0')->change();
            $table->renameColumn('tech_main_action', 'field_3_0_0')->change();
            $table->renameColumn('tech_main_reserve', 'field_3_0_1')->change();
            $table->renameColumn('tech_special_action' ,'field_3_1_0')->change();
            $table->renameColumn('tech_special_reserve', 'field_3_1_1')->change();
            $table->renameColumn('tech_additional_action', 'field_3_2_0')->change();
            $table->renameColumn('tech_additional_reserve', 'field_3_2_1')->change();
            $table->renameColumn('tech_other_action', 'field_3_3_0')->change();
            $table->renameColumn('tech_other_reserve', 'field_3_3_1')->change();
            $table->renameColumn('gsm_gasoline', 'field_4_0_0')->change();
            $table->renameColumn('gsm_diesel', 'field_4_1_0')->change();
            $table->renameColumn('radio_stations', 'field_5_0_0')->change();
            $table->renameColumn('personal_respiratory_protection', 'field_6_0_0')->change();
            $table->renameColumn('personal_protection', 'field_7_0_0')->change();
            $table->renameColumn('other_protection', 'field_8_0_0')->change();



        });
    }
}
