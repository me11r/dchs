<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFormationSaversReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_savers_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('saved_people')->default(0);
            $table->integer('saved_children')->default(0);
            $table->integer('fires')->default(0);
            $table->integer('ignitions')->default(0);
            $table->integer('emergencies')->default(0);
            $table->integer('rescues')->default(0);
            $table->integer('searches')->default(0);
            $table->integer('others')->default(0);
            $table->integer('false_calls')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('formation_savers_report');
    }
}
