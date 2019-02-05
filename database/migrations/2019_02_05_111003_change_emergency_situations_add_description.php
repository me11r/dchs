<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEmergencySituationsAddDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emergency_situations', function (Blueprint $table) {
            $table->longText('description')->after('id')->nullable();
            $table->unsignedInteger('wounded')->after('id')->nullable();
            $table->unsignedInteger('poisoned')->after('id')->nullable();
            $table->unsignedInteger('saved_animals')->after('id')->nullable();

            $table->dropColumn([
                'incident_type',
                'place',
                'city_area_id',
                'object_name',
                'size',
                'died_children',
                'injured_children',
                'hospitalized_children',
                'evacuated_children',
                'saved_children',
                'lost',
                'lost_children',
                'influence',
                'can_fix_themselves',
                'involved',
                'involved_services',
                'involved_people',
                'involved_tech',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emergency_situations', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'wounded',
                'poisoned',
                'saved_animals',
            ]);

            $table->string('incident_type')->nullable();
            $table->string('place');
            $table->integer('city_area_id')->nullable();
            $table->string('object_name')->nullable();
            $table->string('size')->nullable();
            $table->integer('died_children')->nullable();
            $table->integer('injured_children')->nullable();
            $table->integer('hospitalized_children')->nullable();
            $table->integer('evacuated_children')->nullable();
            $table->integer('saved_children')->nullable();
            $table->integer('lost_children')->nullable();
            $table->string('influence')->nullable();
            $table->boolean('can_fix_themselves')->default(0);
            $table->string('involved')->nullable();
            $table->string('involved_services')->nullable();
            $table->string('involved_people')->nullable();
            $table->string('involved_tech')->nullable();
        });
    }
}
