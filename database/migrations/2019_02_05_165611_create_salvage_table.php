<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalvageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salvage', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('fire_department_id')->unsigned();
            $table->date('date');
            $table->integer('value');

            $table->foreign('fire_department_id')->name('fdi')
                ->references('id')->on('fire_departments')->onDelete('cascade');

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
        Schema::dropIfExists('salvage');
    }
}
