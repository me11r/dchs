<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationalGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operational_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $operGroups = [
            'ОГ-1',
            'ОГ-2',
            'ОГ-3',
            'ОГ-4',
        ];

        foreach ($operGroups as $operGroup) {
            \App\OperationalGroup::create([
                'name' => $operGroup
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
        Schema::dropIfExists('operational_groups');
    }
}
