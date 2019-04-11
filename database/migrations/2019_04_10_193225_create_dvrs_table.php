<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDvrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dvrs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('formation_tech_report_id');
            $table->foreign('formation_tech_report_id')
                ->references('id')
                ->on('formation_tech_report')
                ->onDelete('cascade');

            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->boolean('status')->nullable();
            $table->text('note')->nullable();

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
        Schema::dropIfExists('dvrs');
    }
}
