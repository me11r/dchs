<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeServiceTypesAddUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->unsignedInteger('head_user_id')->nullable()->after('id');
            $table->foreign('head_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        $userId = (new \App\User)->orderBy('id', 'ASC')->first()->id ?? null;
        foreach (\App\Models\ServiceType::all() as $item) {
            $item->head_user_id = $userId;
            $item->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->dropForeign(['head_user_id']);
            $table->dropColumn(['head_user_id']);
        });
    }
}
