<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFormationSaversMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_savers_migrations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('formation_savers_report_id')->references('id')->on('formation_savers_report');
            $table->string('route')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->integer('tech_count')->default(0);
            $table->integer('people_count')->default(0);
            $table->string('manager_name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('phone')->nullable();
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
        Schema::dropIfExists('formation_savers_migrations');
    }
}
