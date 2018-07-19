<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add101NewFieldReturnTab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->text('detailed_address')->nullable();
            $table->integer('burn_object_id')->nullable();
            $table->integer('trip_result_id')->nullable();
            $table->integer('liquidation_method_id')->nullable();
            $table->integer('result_fire_level_id')->nullable();
            $table->boolean('vu_found')->default(false);
            $table->boolean('animal_death')->default(false);
            $table->boolean('car_crash')->default(false);
            $table->integer('rescued_count')->nullable();
            $table->integer('evac_count')->nullable();
            $table->integer('co2_poisoned_count')->nullable();
            $table->integer('gpt_burns_count')->nullable();
            $table->integer('people_death_count')->nullable();
            $table->integer('children_death_count')->nullable();
            $table->integer('hospitalized_count')->nullable();
            $table->text('more_info')->nullable();
            $table->boolean('hydrant_found')->default(false);
            $table->decimal('distance', 12, 2)->nullable();
            $table->text('owner')->nullable();
            $table->text('explanation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101', function (Blueprint $table) {
        });
    }
}
