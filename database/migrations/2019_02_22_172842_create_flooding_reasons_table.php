<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFloodingReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flooding_reasons', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index();
            $table->softDeletes();

            $table->timestamps();
        });

        $newData = [
            ['name' => 'засорение арычной сети'],
            ['name' => 'отсутствие арычной сети'],
            ['name' => 'образование ледяных зажоров'],
            ['name' => 'не справляется арычная сеть'],
        ];

        foreach ($newData as $newDatum) {
            \App\FloodingReason::create($newDatum);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flooding_reasons');
    }
}
