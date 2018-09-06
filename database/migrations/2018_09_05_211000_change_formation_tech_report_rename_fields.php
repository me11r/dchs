<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationTechReportRenameFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_tech_report', function (Blueprint $table) {
            $table->dropColumn([
                'field_4_1_0',
                'field_4_1_1',
                'field_4_2_0',
                'field_4_2_1',
                'field_4_3_0',
                'field_4_3_1',
                'field_0',
            ]);

            $table->renameColumn('field_1', 'device');
            $table->renameColumn('field_3_0', 'motor_water_pump');
            $table->renameColumn('field_3_1', 'motor_mud_pump');
            $table->renameColumn('field_5_1_0', 'firehose_125');
            $table->renameColumn('field_5_1_1', 'firehose_75');
            $table->renameColumn('field_5_1_2', 'firehose_77');
            $table->renameColumn('field_5_1_3', 'firehose_51');
            $table->renameColumn('field_5_0', 'barrel_stationary');
            $table->renameColumn('field_5_1', 'barrel_portable');
            $table->renameColumn('field_5_2', 'pgs_600');
            $table->renameColumn('field_5_3', 'purga');
            $table->renameColumn('field_5_4', 'radio_station_portable');
            $table->renameColumn('field_5_5', 'flashlight');
            $table->renameColumn('field_5_6', 'searchlight');
            $table->renameColumn('field_5_7', 'tok_l1');
            $table->renameColumn('field_5_8', 'knapsack_devices');
            $table->renameColumn('field_5_9', 'shovel');
            $table->renameColumn('field_5_10', 'flapper');
            $table->renameColumn('field_5_11', 'life_rope');
            $table->renameColumn('field_5_12', 'foamer');
            $table->renameColumn('field_2', 'foamer_in_stock');
            $table->renameColumn('field_7_1_0', 'damaged_hydrant_street');
            $table->renameColumn('field_7_1_1', 'damaged_hydrant_object');
            $table->renameColumn('field_7_0', 'damaged_pv');
            $table->renameColumn('field_8_0', 'active_gasoline');
            $table->renameColumn('field_8_1', 'active_diesel');
            $table->renameColumn('field_9_0', 'reserved_gasoline');
            $table->renameColumn('field_9_1', 'reserved_diesel');
            $table->renameColumn('field_3', 'generator');
            $table->renameColumn('field_4', 'head_guard');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_tech_report', function (Blueprint $table) {

            $table->text('field_4_1_0')->after('id')->nullable();
            $table->text('field_4_1_1')->after('id')->nullable();
            $table->text('field_4_2_0')->after('id')->nullable();
            $table->text('field_4_2_1')->after('id')->nullable();
            $table->text('field_4_3_0')->after('id')->nullable();
            $table->text('field_4_3_1')->after('id')->nullable();
            $table->text('field_0')->after('id')->nullable();

            $table->renameColumn('device', 'field_1');
            $table->renameColumn('motor_water_pump', 'field_3_0');
            $table->renameColumn('motor_mud_pump', 'field_3_1');
            $table->renameColumn('firehose_125', 'field_5_1_0');
            $table->renameColumn('firehose_75', 'field_5_1_1');
            $table->renameColumn('firehose_77', 'field_5_1_2');
            $table->renameColumn('firehose_51', 'field_5_1_3');
            $table->renameColumn('barrel_stationary', 'field_5_0');
            $table->renameColumn('barrel_portable', 'field_5_1');
            $table->renameColumn('pgs_600', 'field_5_2');
            $table->renameColumn('purga', 'field_5_3');
            $table->renameColumn('radio_station_portable', 'field_5_4');
            $table->renameColumn('flashlight', 'field_5_5');
            $table->renameColumn('searchlight', 'field_5_6');
            $table->renameColumn('tok_l1', 'field_5_7');
            $table->renameColumn('knapsack_devices', 'field_5_8');
            $table->renameColumn('shovel', 'field_5_9');
            $table->renameColumn('flapper', 'field_5_10');
            $table->renameColumn('life_rope', 'field_5_11');
            $table->renameColumn('foamer', 'field_5_12');
            $table->renameColumn('foamer_in_stock', 'field_2');
            $table->renameColumn('damaged_hydrant_street', 'field_7_1_0');
            $table->renameColumn('damaged_hydrant_object', 'field_7_1_1');
            $table->renameColumn('damaged_pv', 'field_7_0');
            $table->renameColumn('active_gasoline', 'field_8_0');
            $table->renameColumn('active_diesel', 'field_8_1');
            $table->renameColumn('reserved_gasoline', 'field_9_0');
            $table->renameColumn('reserved_diesel', 'field_9_1');
            $table->renameColumn('generator', 'field_3');
            $table->renameColumn('head_guard', 'field_4');
        });
    }
}
