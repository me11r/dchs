<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvalancheTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        iF(!Schema::hasTable('avalanche_types')) {
            Schema::create('avalanche_types', function (Blueprint $table) {
                $table->increments('id');

                $table->string('name')->index();

                $table->softDeletes();

                $table->timestamps();
            });
        }

        $newData = [
            ['name' => 'Профилактический'],
            ['name' => 'Самопроизвольный'],
        ];

        foreach ($newData as $newDatum) {
            \App\AvalancheType::create($newDatum);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avalanche_types');
    }
}
