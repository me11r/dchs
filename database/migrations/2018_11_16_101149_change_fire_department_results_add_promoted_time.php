<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFireDepartmentResultsAddPromotedTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->timestamp('promoted_at')->nullable()->after('id');
            $table->string('promoted_department')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->dropColumn([
                'promoted_at',
                'promoted_department',
            ]);
        });
    }
}
