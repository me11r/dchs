<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationRecordsAddPhoneCnt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_records', function (Blueprint $table) {
            $table->string('head_count')->nullable()->after('id');
            $table->string('head_phone')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_records', function (Blueprint $table) {
            $table->dropColumn([
                'head_count',
                'head_phone',
            ]);
        });
    }
}
