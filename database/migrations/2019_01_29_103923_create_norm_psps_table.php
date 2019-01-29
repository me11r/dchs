<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNormPspsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('norm_psps', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('time_begin')->nullable();
            $table->string('time_end')->nullable();

            $table->unsignedInteger('fire_department_id');
            $table->foreign('fire_departments')
                ->references('id')
                ->on('fire_departments')
                ->onDelete('cascade');

            $table->integer('department');

            $table->unsignedInteger('norm_number_id');
            $table->foreign('norm_number_id')
                ->references('id')
                ->on('norm_numbers')
                ->onDelete('cascade');

            $table->unsignedInteger('norm_type_id');
            $table->foreign('norm_type_id')
                ->references('id')
                ->on('norm_types')
                ->onDelete('cascade');

            $table->string('responsible_person')->nullable();

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
        Schema::dropIfExists('norm_psps');
    }
}
