<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNormPspsAddNoteGdzs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('norm_psps', function (Blueprint $table) {
            $table->text('note')->nullable()->after('id');
            $table->boolean('gdzs_included_30')->nullable()->after('id');
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
            $table->dropColumn([
                'note',
                'gdzs_included_30',
            ]);
        });
    }
}
