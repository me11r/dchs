<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchFallReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_fall_reasons', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index();

            $table->softDeletes();
            $table->timestamps();
        });

        $newData = [
            ['name' => 'Порывистый ветер',],
            ['name' => 'Переувлажнение в результате дождя',],
        ];

        foreach ($newData as $newDatum) {
            \App\BranchFallReason::firstOrCreate($newDatum);
        }

        $dict = \App\Dictionary::create([
            'table' => 'branch_fall_reasons',
            'title' => 'Причины падение веток и деревьев',
            'dictionary_category_id' => \App\DictionaryCategory::name('112')->first()->id ?? null,
            'sort_order' => 10,
            'model' => \App\BranchFallReason::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_fall_reasons');
        $dict = \App\Dictionary::name('branch_fall_reasons')->forceDelete();
    }
}
