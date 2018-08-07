<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMedicalFormationReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_medical_report', function (Blueprint $table) {
            $table->increments('id');
            $table->date('report_date');
            $table->string('manager')->nullable();
            $table->integer('staffed')->default(0);
            $table->integer('on_duty')->default(0);
            $table->string('formation')->nullable();
            $table->integer('people')->default(0);
            $table->integer('tech')->default(0);
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
        Schema::dropIfExists('formation_medical_report');
    }
}
