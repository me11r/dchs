<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeHydrantsAddCorrectionDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hydrants', function (Blueprint $table) {
            $table->date('correction_date')->nullable()->after('id');
            $table->string('operator_name')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hydrants', function (Blueprint $table) {
            $table->dropColumn(['correction_date', 'operator_name']);
        });
    }
}
