<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationPersonsItemsAddDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_persons_items', function (Blueprint $table) {
            $table->text('comment')->after('rank')->nullable();
            $table->date('date_from')->after('rank')->nullable();
            $table->date('date_to')->after('rank')->nullable();
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
            $table->dropColumn([
                'comment',
                'date_from',
                'date_to',
            ]);
        });
    }
}
