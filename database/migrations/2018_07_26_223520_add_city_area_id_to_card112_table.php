<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityAreaIdToCard112Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function(Blueprint $table){
            $table->integer('city_area_id');
        });

        foreach (\App\Models\Card112\Card112::all() as $item){
            $item->city_area_id = 1;
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
        Schema::table('card_112', function(Blueprint $table){
            $table->dropColumn('city_area_id');
        });
    }
}
