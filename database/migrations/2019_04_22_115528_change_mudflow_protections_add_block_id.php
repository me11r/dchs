<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMudflowProtectionsAddBlockId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mudflow_protections', function (Blueprint $table) {
            $table->unsignedInteger('block_id')->after('id')->nullable();
            $table->foreign('block_id')
                ->references('id')
                ->on('mudflow_protection_blocks')
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
        Schema::table('mudflow_protections', function (Blueprint $table) {
            $table->dropForeign(['block_id']);
            $table->dropColumn(['block_id']);
        });
    }
}
