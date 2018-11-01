<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormationOdPersonItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_od_person_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('staff_id');

            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')
                ->on('formation_persons_report')
                ->onDelete('cascade');

            $table->string('status')->default('active')->nullable();
            $table->string('rank')->nullable();

            $table->date('date_to')->nullable();
            $table->date('date_from')->nullable();
            $table->text('comment')->nullable();

            $table->string('table_name')->index();


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
        Schema::dropIfExists('formation_od_person_items');
    }
}
