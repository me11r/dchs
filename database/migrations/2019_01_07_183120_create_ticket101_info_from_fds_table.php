<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicket101InfoFromFdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket101_info_from_fds', function (Blueprint $table) {
            $table->increments('id');
            $table->text('detailed_address')->nullable();
            $table->integer('burn_object_id')->nullable();
            $table->integer('living_sector_type_id')->nullable();
            $table->integer('trip_result_id')->nullable();
            $table->integer('liquidation_method_id')->nullable();
            $table->integer('result_fire_level_id')->nullable();
            $table->string('max_square')->nullable();
            $table->boolean('vu_found')->default(false);
            $table->boolean('animal_death')->default(false);
            $table->boolean('car_crash')->default(false);
            $table->integer('rescued_count')->nullable();
            $table->integer('evac_count')->nullable();
            $table->integer('co2_poisoned_count')->nullable();
            $table->integer('ch4_poisoned_count')->nullable();
            $table->integer('gpt_burns_count')->nullable();
            $table->integer('people_death_count')->nullable();
            $table->integer('children_death_count')->nullable();
            $table->integer('hospitalized_count')->nullable();
            $table->text('ticket_result')->nullable();
            $table->text('special_tech')->nullable();
            $table->text('more_info')->nullable();
            $table->unsignedInteger('water_supply_source_id')->nullable();
            $table->foreign('water_supply_source_id')
                ->references('id')
                ->on('water_supply_sources')
                ->onDelete('cascade');
            $table->timestamps();
            $table->decimal('distance', 12, 2)->nullable();
            $table->text('owner')->nullable();
            $table->unsignedInteger('ticket_id')->nullable();
            $table->foreign('ticket_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');
            $table->unsignedInteger('fire_department_id');
            $table->foreign('fire_department_id')
                ->references('id')
                ->on('fire_departments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket101_info_from_fds');
    }
}
