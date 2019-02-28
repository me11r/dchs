<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiseaseTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index();

            $table->softDeletes();
            $table->timestamps();
        });

        $newData = [
            ['name' => 'холера'],
            ['name' => 'дизентерия'],
            ['name' => 'сальмонеллёз'],
            ['name' => 'эшерихиоз'],
        ];

        foreach ($newData as $newDatum) {
            \App\DiseaseType::create($newDatum);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disease_types');
    }
}
