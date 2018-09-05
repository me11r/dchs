<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormationPersonsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_persons_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('staff_id');
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->onDelete('cascade');

            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')
                ->on('formation_persons_report')
                ->onDelete('cascade');

            $table->string('status')->default('active')->nullable();
            $table->string('rank')->nullable();

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
        Schema::dropIfExists('formation_persons_items');
    }
}
