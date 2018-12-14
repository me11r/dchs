<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFireDepartmentChecksFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_department_checks', function (Blueprint $table) {
            $table->dropColumn([
                'user',
                'fire_dept',
                'date',
            ]);

            $table->time('time_begin')->nullable()->after('id');
            $table->time('time_end')->nullable()->after('id');

            $table->unsignedInteger('fire_department_id')->nullable()->after('id');
            $table->foreign('fire_department_id')
                ->references('id')
                ->on('fire_departments')
                ->onDelete('cascade');

            $table->string('responsible_person')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_department_checks', function (Blueprint $table) {
            $table->text('user')->nullable()->after('id');
            $table->text('fire_dept')->nullable()->after('id');
            $table->text('date')->nullable()->after('id');

            $table->dropForeign(['fire_department_id']);

            $table->dropColumn(['fire_department_id']);
            $table->dropColumn(['responsible_person']);
            $table->dropColumn(['time_begin']);
            $table->dropColumn(['time_end']);
        });
    }
}
