<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChageTicket101AddSavedPpl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->unsignedInteger('saved_vehicles')->after('id')->nullable();
            $table->unsignedInteger('saved_children')->after('id')->nullable();
            $table->unsignedInteger('bodies_extracted')->after('id')->nullable();
            $table->unsignedInteger('children_bodies_extracted')->after('id')->nullable();
            $table->unsignedInteger('medical_care_provided')->after('id')->nullable();
            $table->unsignedInteger('children_medical_care_provided')->after('id')->nullable();
            $table->unsignedInteger('children_evacuated')->after('id')->nullable();
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
            $table->dropColumn([
               'saved_vehicles',
               'saved_children',
               'bodies_extracted',
               'children_bodies_extracted',
               'medical_care_provided',
               'children_medical_care_provided',
               'children_evacuated',
            ]);
        });
    }
}
