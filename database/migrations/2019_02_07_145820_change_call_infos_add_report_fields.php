<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCallInfosAddReportFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_infos', function (Blueprint $table) {
            $table->integer('count_102')->after('id')->nullable();
            $table->integer('count_103')->after('id')->nullable();
            $table->integer('count_info')->after('id')->nullable();
            $table->integer('count_other')->after('id')->nullable();
            $table->integer('count_emergency')->after('id')->nullable();
            $table->text('note')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_infos', function (Blueprint $table) {
            $table->dropColumn([
                'count_102',
                'count_103',
                'count_info',
                'count_other',
                'count_emergency',
                'note',
            ]);
        });
    }
}
