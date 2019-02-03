<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101AddObjectClassificationId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->unsignedInteger('object_classification_id')->after('id')->nullable();
            $table->foreign('object_classification_id')
                ->references('id')
                ->on('object_classifications')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->dropForeign(['object_classification_id']);
            $table->dropColumn(['object_classification_id']);
        });
    }
}
