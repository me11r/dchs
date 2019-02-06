<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrillTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drill_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->softDeletes();
            $table->timestamps();
        });

        foreach ([
                     'РКШУ',
                     'ТСУ',
                     'ПТУ',
                     'ПТЗ',
                     'ТДК',
                     'Учения'
                 ] as $item) {

            \App\DrillType::create(['name' => $item]);

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drill_types');
    }
}
