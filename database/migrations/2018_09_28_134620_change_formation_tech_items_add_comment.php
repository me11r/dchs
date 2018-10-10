<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationTechItemsAddComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_tech_items', function (Blueprint $table) {
            $table->text('comment')->after('reserve')->nullable();
            $table->date('date_from')->after('reserve')->nullable();
            $table->date('date_to')->after('reserve')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_tech_items', function (Blueprint $table) {
            $table->dropColumn([
                'comment',
                'date_from',
                'date_to',
            ]);
        });
    }
}
