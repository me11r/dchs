<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101AddCreatedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('id');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->unsignedInteger('changed_by')->nullable()->after('id');
            $table->foreign('changed_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
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
            $table->dropForeign(['created_by']);
            $table->dropForeign(['changed_by']);

            $table->dropColumn([
                'created_by',
                'changed_by',
            ]);
        });
    }
}
