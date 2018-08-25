<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('fire_department_main_id');
            $table->foreign('fire_department_main_id')
                ->references('id')
                ->on('fire_departments')
                ->onDelete('cascade');

            $table->unsignedInteger('fire_department_id');
            $table->foreign('fire_department_id')
                ->references('id')
                ->on('fire_departments')
                ->onDelete('cascade');

            $table->unsignedInteger('dict_fire_level_id');
            $table->foreign('dict_fire_level_id')
                ->references('id')
                ->on('dict_fire_level')
                ->onDelete('cascade');

            $table->boolean('is_reserved')->default(false);
            $table->string('department')->nullable();

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
        Schema::dropIfExists('schedules');
    }
}
