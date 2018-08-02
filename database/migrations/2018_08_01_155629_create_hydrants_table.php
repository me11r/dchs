<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHydrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hydrants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address', 1000);
            $table->text('specification');
            $table->integer('fire_department_id')->unsigned();
            $table->double('lat');
            $table->double('long');

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
        Schema::dropIfExists('hydrants');
    }
}
