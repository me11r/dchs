<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertSystemCheckItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_system_check_items', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('check1')->nullable();
            $table->boolean('check2')->nullable();
            $table->boolean('check3')->nullable();

            $table->unsignedInteger('direction_id');
            $table->foreign('direction_id')
                ->references('id')
                ->on('directions')
                ->onDelete('cascade');

            $table->unsignedInteger('alert_system_check_id');
            $table->foreign('alert_system_check_id')
                ->references('id')
                ->on('alert_system_checks')
                ->onDelete('cascade');

            $table->timestamps();
        });

        $check = \App\AlertSystemCheck::create(['date' => now()]);
        foreach (\App\Direction::all() as $direction) {
            \App\AlertSystemCheckItem::create([
                'direction_id' => $direction->id,
                'alert_system_check_id' => $check->id,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_system_check_items');
    }
}
