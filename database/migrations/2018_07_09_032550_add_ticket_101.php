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
            $table->text('location');
            $table->text('city_area');
            $table->text('crossroad');
            $table->text('description');
            $table->integer('storey_count');
            $table->integer('floor');
            $table->text('building_description');
            $table->text('caller_phone');
            $table->text('caller_name');
            $table->text('call_time')->nullable();
            // tab 2
            $table->text('notify_100_time')->nullable();
            $table->text('notify_101_time')->nullable();
            $table->text('notify_102_time')->nullable();
            $table->text('notify_103_time')->nullable();
            $table->text('notify_104_time')->nullable();
            $table->text('notify_b01_time')->nullable();
            $table->text('notify_b04_time')->nullable();
            $table->text('next_notify_time')->nullable();

            $table->text('call_112_time')->nullable();
            $table->text('name_112_recv');
            $table->text('arrival_112')->nullable();

            $table->text('call_102_time')->nullable();
            $table->text('name_102_recv');
            $table->text('arrival_102')->nullable();

            $table->text('call_103_time')->nullable();
            $table->text('name_103_recv');
            $table->text('arrival_103')->nullable();

            $table->text('call_104_time')->nullable();
            $table->text('name_104_recv');
            $table->text('arrival_104')->nullable();

            $table->text('call_electro_time')->nullable();
            $table->text('name_electro_recv');
            $table->text('arrival_electro')->nullable();

            $table->text('call_water_time')->nullable();
            $table->text('name_water_recv');
            $table->text('arrival_water')->nullable();

            $table->text('call_smk_time')->nullable();
            $table->text('name_smk_recv');
            $table->text('arrival_smk')->nullable();
            // tab 3

            for ($i = 1; $i < 18; $i++) {
                $table->text('ph_' . $i. '_ot');
                $table->text('ph_'. $i. '_out_time');
                $table->text('ph_'. $i. '_arrive_time');
                $table->text('ph_'. $i. '_loc_time');
                $table->text('ph_'.$i.'_liqv_time');
                $table->text('ph_'.$i. '_ret_time');
            }

            // tab 4

            for($i = 1; $i < 6; $i++) {
                $table->text('update_'.$i.'_time');
                $table->text('update_'.$i.'_info');
            }

            $table->longText('add_info');


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
