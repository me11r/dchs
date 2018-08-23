<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmergencySituationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_situations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('incident_type')->nullable();
            $table->date('date');
            $table->time('time');
            $table->string('place');

            $table->integer('city_area_id')->nullable();
            $table->string('location ', 1000)->nullable();
            $table->string('object_name')->nullable();
            $table->string('size')->nullable();

            $table->integer('died')->nullable();
            $table->integer('died_children')->nullable();
            $table->integer('injured')->nullable();
            $table->integer('injured_children')->nullable();
            $table->integer('hospitalized')->nullable();
            $table->integer('hospitalized_children')->nullable();
            $table->integer('evacuated')->nullable();
            $table->integer('evacuated_children')->nullable();
            $table->integer('saved')->nullable();
            $table->integer('saved_children')->nullable();
            $table->integer('lost')->nullable();
            $table->integer('lost_children')->nullable();
            $table->string('influence')->nullable();
            $table->boolean('can_fix_themselves')->default(0);
            $table->string('involved')->nullable();
            $table->string('involved_services')->nullable();
            $table->string('involved_people')->nullable();
            $table->string('involved_tech')->nullable();

            $table->integer('user_id')->unsigned();

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
        Schema::dropIfExists('emergency_situations');
    }
}
