<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFormationMudflowReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_mudflow_report', function (Blueprint $table) {
            $table->increments('id');
            $table->date('report_date');
            $table->string('formation')->nullable();
            $table->string('position')->nullable();
            $table->string('name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('phone')->nullable();
            $table->text('resources')->nullable();
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
        Schema::dropIfExists('formation_mudflow_report');
    }
}
