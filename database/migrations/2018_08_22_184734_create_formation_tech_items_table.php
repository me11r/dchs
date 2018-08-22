<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormationTechItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_tech_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('vehicle_id');
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->onDelete('cascade');

            $table->unsignedInteger('formation_tech_report_id');
            $table->foreign('formation_tech_report_id')
                ->references('id')
                ->on('formation_tech_report')
                ->onDelete('cascade');

            $table->unsignedInteger('department')->default(1);
            $table->string('status');

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
        Schema::dropIfExists('formation_tech_items');
    }
}
