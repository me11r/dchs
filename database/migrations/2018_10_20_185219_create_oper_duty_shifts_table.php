<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperDutyShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oper_duty_shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('direction')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        $ods = [
            ['name' => 'ОДС 1'],
            ['name' => 'ОДС 2'],
            ['name' => 'ОДС 3'],
            ['name' => 'ОДС 4'],
        ];

        foreach ($ods as $item){
            \App\OperDutyShift::firstOrCreate($item);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oper_duty_shifts');
    }
}
