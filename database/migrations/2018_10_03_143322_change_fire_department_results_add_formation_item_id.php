<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFireDepartmentResultsAddFormationItemId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->unsignedInteger('tech_id')->after('id')->nullable();
            $table->foreign('tech_id')
                ->references('id')
                ->on('formation_tech_items')
                ->onDelete('cascade');

            $table->boolean('recommended')->default(false)->nullable()->after('id');

            $table->dropColumn(['departments']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->dropForeign(['tech_id']);
            $table->dropColumn(['tech_id', 'recommended']);
            $table->string('departments')->nullable()->after('dispatched');
        });
    }
}
