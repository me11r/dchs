<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToHydrants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hydrants', function (Blueprint $table) {
            $table->string('number')->default('')->nullable();
            $table->boolean('active')->default(1)->nullable();
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
            $table->dropColumn('number');
            $table->dropColumn('active');
        });
    }
}
