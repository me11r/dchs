<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperDutyShiftStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oper_duty_shift_staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->timestamps();
        });

        $arr = [
            ['name' => 'Чистякова А.Е.'],
            ['name' => 'Жаугашев К.Б.'],
            ['name' => 'Берикбаева Д.А.'],
            ['name' => 'Попова Д.О.'],
            ['name' => 'Серикбаев Н.Д'],
            ['name' => 'Сейсенов Ж.О.'],
            ['name' => 'Куанышева К.М.'],
            ['name' => 'Ахунов Ф.Н.'],
            ['name' => 'Минтаева Г.А.'],
            ['name' => 'Искаков А.С.'],
            ['name' => 'Карымбаев Д.Ж.'],
        ];

        foreach ($arr as $item) {
            \App\OperDutyShiftStaff::firstOrCreate($item);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oper_duty_shift_staffs');
    }
}
