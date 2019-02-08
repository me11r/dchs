<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNormPspDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('norm_psp_departments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name')->index();
            $table->unsignedInteger('norm_id');

            $table->foreign('norm_id')
                ->references('id')
                ->on('norm_psps')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::table('norm_psps', function (Blueprint $table) {
            $table->dropColumn(['department']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('norm_psp_departments');

        Schema::create('norm_psps', function (Blueprint $table) {
            $table->integer('department')->after('id')->nullable();
        });
    }
}
