<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationDistrictManagerItemsAddDatesInactiveType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_district_manager_items', function (Blueprint $table) {
            $table->string('inactive_type')->after('id')->nullable()->index();
            $table->date('date_from')->after('id')->nullable();
            $table->date('date_to')->after('id')->nullable();
            $table->text('comment')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_district_manager_items', function (Blueprint $table) {
            $table->dropIndex(['inactive_type']);
            $table->dropColumn([
                'inactive_type',
                'date_from',
                'date_to',
                'comment',
            ]);
        });
    }
}
