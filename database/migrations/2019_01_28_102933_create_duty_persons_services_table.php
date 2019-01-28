<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDutyPersonsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duty_persons_services', function (Blueprint $table) {
            $table->increments('id');

            $table->date('date');

            $table->string('police_dept102')->nullable();
            $table->string('ambulance103')->nullable();
            $table->string('gas_service104')->nullable();

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
        Schema::dropIfExists('duty_persons_services');
    }
}
