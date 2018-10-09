<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCard112Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->integer('injured_hard')->default(0);
            $table->integer('poisoned')->default(0);
            $table->integer('saved')->default(0);
            $table->integer('saved_animals')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->dropColumn('injured_hard');
            $table->dropColumn('poisoned');
            $table->dropColumn('saved');
            $table->dropColumn('saved_animals');
        });
    }
}
