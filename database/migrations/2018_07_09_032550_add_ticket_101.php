<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTicket101 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket101', function (Blueprint $table) {
            $table->increments('id');
            //tab 1
            $table->text('location')->nullable();
            $table->integer('city_area_id')->nullable();
            $table->integer('crossroad_1_id')->nullable();
            $table->integer('crossroad_2_id')->nullable();
            $table->integer('fire_object_id')->nullable();
            $table->integer('storey_count')->nullable();
            $table->integer('floor')->nullable();
            $table->text('building_description')->nullable();
            $table->boolean('people_in_danger')->default(false);
            $table->integer('fire_level_id')->nullable();
            $table->text('caller_phone')->nullable();
            $table->text('caller_name')->nullable();
            $table->time('call_time')->nullable();
            // tab 2
            $table->time('notify_100_time')->nullable();
            $table->time('notify_101_time')->nullable();
            $table->time('notify_102_time')->nullable();
            $table->time('notify_103_time')->nullable();
            $table->time('notify_104_time')->nullable();
            $table->time('notify_b01_time')->nullable();
            $table->time('notify_b04_time')->nullable();
            $table->time('next_notify_time')->nullable();

            $table->time('call_112_time')->nullable();
            $table->text('name_112_recv')->nullable();
            $table->text('arrival_112')->nullable();

            $table->time('call_102_time')->nullable();
            $table->text('name_102_recv')->nullable();
            $table->text('arrival_102')->nullable();

            $table->time('call_103_time')->nullable();
            $table->text('name_103_recv')->nullable();
            $table->text('arrival_103')->nullable();

            $table->time('call_104_time')->nullable();
            $table->text('name_104_recv')->nullable();
            $table->text('arrival_104')->nullable();

            $table->time('call_electro_time')->nullable();
            $table->text('name_electro_recv')->nullable();
            $table->text('arrival_electro')->nullable();

            $table->time('call_water_time')->nullable();
            $table->text('name_water_recv')->nullable();
            $table->text('arrival_water')->nullable();

            $table->time('call_smk_time')->nullable();
            $table->text('name_smk_recv')->nullable();
            $table->text('arrival_smk')->nullable();
            // tab 3

            for ($i = 1; $i < 18; $i++) {
                $table->text('ph_' . $i . '_ot')->nullable();
                $table->time('ph_' . $i . '_out_time')->nullable();
                $table->time('ph_' . $i . '_arrive_time')->nullable();
                $table->time('ph_' . $i . '_loc_time')->nullable();
                $table->time('ph_' . $i . '_liqv_time')->nullable();
                $table->time('ph_' . $i . '_ret_time')->nullable();
                $table->boolean('ph_' . $i . '_dispatched')->default(false);
                $table->integer('ph_' . $i . '_dispatch_id')->nullable();
            }

            // tab 4

            for ($i = 1; $i < 6; $i++) {
                $table->time('update_' . $i . '_time')->nullable();
                $table->text('update_' . $i . '_info')->nullable();
            }

            $table->longText('add_info')->nullable();


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket101');
    }
}
