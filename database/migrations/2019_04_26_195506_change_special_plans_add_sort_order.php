<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSpecialPlansAddSortOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_plans', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->nullable()->after('id');
        });

        foreach (\App\Models\SpecialPlan::all() as $key => $item) {
            $item->sort_order = ++$key;
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
        Schema::table('special_plans', function (Blueprint $table) {
            $table->dropColumn(['sort_order']);
        });
    }
}
