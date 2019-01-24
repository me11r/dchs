<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationPersonsItemsAddTraineeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_persons_items', function (Blueprint $table) {
            $table->string('trainee_type')->nullable()->after('rank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_persons_items', function (Blueprint $table) {
            $table->dropColumn(['trainee_type']);
        });
    }
}
