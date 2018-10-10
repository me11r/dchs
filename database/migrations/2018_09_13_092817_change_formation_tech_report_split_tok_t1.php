<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationTechReportSplitTokT1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_tech_report', function (Blueprint $table) {
            $table->dropColumn(['tok_l1']);
            $table->string('tok')->nullable()->after('searchlight');
            $table->string('l1')->nullable()->after('searchlight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_tech_report', function (Blueprint $table) {
            $table->dropColumn(['tok','l1']);
            $table->string('tok_l1')->nullable()->after('searchlight');
        });
    }
}
