<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSpecialPlansAddSortOrderNewOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_plans', function (Blueprint $table) {
            foreach (\App\Models\SpecialPlan::all()->groupBy('fire_department_id') as $portion) {
                foreach ($portion as $key => $item) {
                    if($item->fire_department_id !== 18) {
                        $item->sort_order = ++$key;
                    }
                    else {
                        $item->sort_order = $item->operational_plan->name;
                    }
                    $item->save();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('special_plans', function (Blueprint $table) {
            foreach (\App\Models\SpecialPlan::all() as $key => $item) {
                $item->sort_order = ++$key;
                $item->save();
            }
        });
    }
}
