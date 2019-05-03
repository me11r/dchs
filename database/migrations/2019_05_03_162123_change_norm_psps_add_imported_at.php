<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNormPspsAddImportedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('norm_psps', function (Blueprint $table) {
            $table->timestamp('imported_at')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('norm_psps', function (Blueprint $table) {
            $table->dropColumn(['imported_at']);
        });
    }
}
