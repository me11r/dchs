<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCard102sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card102s', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('closed')->nullable()->default(false);
            $table->text('location')->nullable();
            $table->unsignedInteger('city_area_id')->nullable();
            $table->longText('detailed_address')->nullable();
            $table->string('caller_phone')->nullable();
            $table->string('caller_name')->nullable();
            $table->string('call_time')->nullable();
            $table->longText('add_info')->nullable();
            $table->longText('add_info2')->nullable();
            $table->string('trip_result')->nullable();
            $table->text('trip_result_add')->nullable();
            $table->string('register_time')->nullable();
            $table->text('object_name')->nullable();
            $table->longText('pre_information')->nullable();
            $table->string('incident_type_id')->nullable();

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
        Schema::dropIfExists('card102s');
    }
}
