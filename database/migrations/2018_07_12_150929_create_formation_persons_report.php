<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormationPersonsReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $fields = [
            'field_0',
            'field_2_0',
            'field_2_1',
            'field_2_2',
            'field_2_3',
            'field_2_4',
            'field_2_5',
            'field_3_0',
            'field_3_1',
            'field_3_2',
            'field_3_3',
            'field_3_4',
            'field_3_5'];
        Schema::create('formation_persons_report', function (Blueprint $table) use ($fields) {
            $table->increments('id');
            $table->integer('form_id');
            $table->integer('dept_id');
            foreach ($fields as $field) {
                $table->text($field)->nullable();
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formation_persons_report');
    }
}
