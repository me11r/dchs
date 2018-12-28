<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStaffCppsAddSurname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_cpps', function (Blueprint $table) {
            $table->string('surname')->nullable()->after('id');
            $table->string('patronymic')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_cpps', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'patronymic',
            ]);
        });
    }
}
