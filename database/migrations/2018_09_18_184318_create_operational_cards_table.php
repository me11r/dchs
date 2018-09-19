<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationalCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operational_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fire_department_id');
            $table->foreign('fire_department_id')
                ->references('id')
                ->on('fire_departments')
                ->onDelete('cascade');

            $table->unsignedInteger('fire_level_id');
            $table->foreign('fire_level_id')
                ->references('id')
                ->on('dict_fire_level')
                ->onDelete('cascade');

            $table->string('oc_number');
            $table->string('object_name')->nullable();
            $table->string('location')->index();
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
        Schema::dropIfExists('operational_cards');
    }
}
