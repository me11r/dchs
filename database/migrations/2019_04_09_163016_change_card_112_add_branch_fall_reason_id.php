<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddBranchFallReasonId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->unsignedInteger('branch_fall_reason_id')->nullable()->after('id');
            $table->foreign('branch_fall_reason_id')
                ->references('id')
                ->on('branch_fall_reasons')
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
        Schema::table('card_112', function (Blueprint $table) {
            $table->dropForeign(['branch_fall_reason_id']);
            $table->dropColumn(['branch_fall_reason_id']);
        });
    }
}
