<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeVehiclesAddYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('base')->nullable()->after('id')->comment('На базе (шасси)');
            $table->string('purpose')->nullable()->after('id');
            $table->integer('publish_year')->nullable()->after('id');
            $table->string('number_old')->nullable()->after('id');
            $table->string('reg_certificate')->comment('номер свидетельства регистрации')->nullable()->after('id');
            $table->text('note')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'base',
                'purpose',
                'publish_year',
                'number_old',
                'reg_certificate',
                'note',
            ]);
        });
    }
}
