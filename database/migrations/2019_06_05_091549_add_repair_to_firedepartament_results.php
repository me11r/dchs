<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRepairToFiredepartamentResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_department_results', function(Blueprint $table) {

                $table->string('repair_department')->nullable();
                $table->timestamp('repair_at')->nullable();

         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_department_results', function(Blueprint $table) {

                $table->dropColumn('repair_department');
                $table->dropColumn('repair_at');

         });
    }
}
