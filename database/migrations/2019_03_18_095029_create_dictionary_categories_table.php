<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDictionaryCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionary_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->integer('sort_order')->index()->nullable()->default(10);
            $table->timestamps();
        });

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => DictionaryCategorySeeder::class,
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dictionary_categories');
    }
}
