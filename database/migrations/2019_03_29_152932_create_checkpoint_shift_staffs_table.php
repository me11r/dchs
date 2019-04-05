<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckpointShiftStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkpoint_shift_staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();

            $table->unsignedInteger('guard_number_id')->nullable();

            $table->string('position')->nullable()->index();
            $table->string('city')->nullable();
            $table->string('rank')->nullable()->index();
            $table->string('military_rank')->nullable();


            $table->softDeletes();
            $table->timestamps();
        });

        $dictionary = [
            'sort_order' => 20,
            'dictionary_category_id' => \App\DictionaryCategory::name('Личный состав 112')->first()->id ?? null,
            'table' => 'checkpoint_shift_staffs',
            'title' => 'ЛС Дежурной смены контрольно-пропускного режима Департамента',
            'model' => \App\CheckpointShiftStaff::class,
        ];

        \App\Dictionary::create($dictionary);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Dictionary::name('checkpoint_shift_staffs')->forceDelete();

        Schema::dropIfExists('checkpoint_shift_staffs');
    }
}
