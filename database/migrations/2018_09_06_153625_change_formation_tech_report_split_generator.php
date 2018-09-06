<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationTechReportSplitGenerator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_tech_report', function (Blueprint $table) {
            $table->dropColumn(['head_guard']);

            $table->string('exhauster')->after('generator')->nullable();
            $table->string('girs')->after('generator')->nullable();
            $table->string('iup')->after('generator')->nullable();
            $table->unsignedInteger('head_guard_id')->nullable()->after('generator');

            $table->foreign('head_guard_id')
                ->references('id')
                ->on('staff')
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
        Schema::table('formation_tech_report', function (Blueprint $table) {
            $table->dropForeign(['head_guard_id']);
            $table->dropColumn([
                'exhauster',
                'girs',
                'iup',
                'head_guard_id',
            ]);
            $table->text('head_guard')->after('id')->nullable();
        });
    }
}
