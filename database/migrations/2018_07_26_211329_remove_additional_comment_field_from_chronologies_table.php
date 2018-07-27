<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAdditionalCommentFieldFromChronologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards_112_chronologies', function(Blueprint $table){
            $table->dropColumn('additional_comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards_112_chronologies', function(Blueprint $table){
            $table->text('additional_comment');
        });
    }
}
