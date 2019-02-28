<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddDiseaseTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->string('name_disease')->nullable()->after('id');

            $table->unsignedInteger('disease_type_id')->nullable()->after('id');
            $table->foreign('disease_type_id')
                ->references('id')
                ->on('disease_types')
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
        Schema::table('card_112', function (Blueprint $table) {
            $table->dropForeign(['disease_type_id']);
            $table->dropColumn([
                'name_disease',
                'disease_type_id',
            ]);
        });
    }
}
