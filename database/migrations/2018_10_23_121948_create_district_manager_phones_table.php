<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictManagerPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_manager_phones', function (Blueprint $table) {
            $table->increments('id');

            $table->string('phone',20);

            $table->unsignedInteger('district_manager_id');
            $table->foreign('district_manager_id')
                ->references('id')
                ->on('district_managers')
                ->onDelete('cascade');

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
        Schema::dropIfExists('district_manager_phones');
    }
}
