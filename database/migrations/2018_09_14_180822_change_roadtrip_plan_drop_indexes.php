<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRoadtripPlanDropIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        \App\RoadtripPlan::where('id', '>', 0)->delete();
        \App\Ticket101::where('id', '>', 0)->delete();

        \App\RoadtripPlan::truncate();
        \App\Ticket101::truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $doctrineTable = $sm->listTableDetails('roadtrip_plan');

        Schema::table('roadtrip_plan', function (Blueprint $table) use ($doctrineTable) {
            if($doctrineTable->hasIndex('roadtrip_plan_department_id_index')){
                $table->dropIndex(['department_id']);
            }

            if($doctrineTable->hasIndex('roadtrip_plan_card_id_index')){
                $table->dropIndex(['card_id']);
            }

            $table->unsignedInteger('card_id')->change();
            $table->unsignedInteger('department_id')->change();
        });

        Schema::table('roadtrip_plan', function (Blueprint $table) use ($doctrineTable) {

            $table->foreign('card_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');

            $table->foreign('department_id')
                ->references('id')
                ->on('fire_departments')
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
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $doctrineTable = $sm->listTableDetails('roadtrip_plan');

        Schema::table('roadtrip_plan', function (Blueprint $table) use ($doctrineTable) {

            if(!$doctrineTable->hasIndex('roadtrip_plan_department_id_index')){
                $table->index('department_id');
            }

            if(!$doctrineTable->hasIndex('roadtrip_plan_card_id_index')){
                $table->index('card_id');
            }

            $table->integer('card_id')->insigned(false)->change();
            $table->integer('department_id')->insigned(false)->change();
        });

        Schema::table('roadtrip_plan', function (Blueprint $table) {
            $table->dropForeign(['card_id']);
            $table->dropForeign(['department_id']);
        });
    }
}
