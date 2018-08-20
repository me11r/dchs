<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_records', function (Blueprint $table) {
            $table->increments('id');

            $table->string('organisation', 50);
            $table->timestamp('date');

            $table->integer('field_1_0_0')->nullable();
            $table->integer('field_2_0_0')->nullable();
            $table->integer('field_2_1_0')->nullable();
            $table->integer('field_2_2_0')->nullable();
            $table->integer('field_3_0_0')->nullable();
            $table->integer('field_3_0_1')->nullable();
            $table->integer('field_3_1_0')->nullable();
            $table->integer('field_3_1_1')->nullable();
            $table->integer('field_3_2_0')->nullable();
            $table->integer('field_3_2_1')->nullable();
            $table->integer('field_3_3_0')->nullable();
            $table->integer('field_3_3_1')->nullable();
            $table->integer('field_4_0_0')->nullable();
            $table->integer('field_4_1_0')->nullable();
            $table->integer('field_5_0_0')->nullable();
            $table->integer('field_6_0_0')->nullable();
            $table->integer('field_7_0_0')->nullable();
            $table->integer('field_8_0_0')->nullable();

            $table->timestamps();

            $table->index(['organisation']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formation_records');
    }
}
