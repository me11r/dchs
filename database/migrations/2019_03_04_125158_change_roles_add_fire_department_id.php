<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRolesAddFireDepartmentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedInteger('hydrant_access_id')->after('id')->nullable()->comment('в рамках пч');
            $table->foreign('hydrant_access_id')
                ->references('id')
                ->on('fire_departments')
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
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['hydrant_access_id']);
            $table->dropColumn(['hydrant_access_id']);
        });
    }
}
