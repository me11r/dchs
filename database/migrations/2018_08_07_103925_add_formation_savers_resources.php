<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFormationSaversResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_savers_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('formation_savers_report_id')->references('id')->on('formation_savers_report');
            $table->string('formation')->nullable();
            $table->integer('staff_count')->default(0);
            $table->integer('people_count')->default(0);
            $table->integer('on_duty')->default(0);
            $table->integer('tech_count')->default(0);
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
        Schema::dropIfExists('formation_savers_resources');
    }
}
