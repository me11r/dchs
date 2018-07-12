<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormationTechReport extends Migration
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
            'field_1',
            'field_3_0',
            'field_3_1',
            'field_4_1_0',
            'field_4_1_1',
            'field_4_2_0',
            'field_4_2_1',
            'field_4_3_0',
            'field_4_3_1',
            'field_5_1_0',
            'field_5_1_1',
            'field_5_1_2',
            'field_5_1_3',
            'field_5_0',
            'field_5_1',
            'field_5_2',
            'field_5_3',
            'field_5_4',
            'field_5_5',
            'field_5_6',
            'field_5_7',
            'field_5_8',
            'field_5_9',
            'field_5_10',
            'field_5_11',
            'field_5_12',
            'field_2',
            'field_7_1_0',
            'field_7_1_1',
            'field_7_0',
            'field_8_0',
            'field_8_1',
            'field_9_0',
            'field_9_1',
            'field_3',
            'field_4',];
        Schema::create('formation_tech_report', function (Blueprint $table) use ($fields) {
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
        Schema::dropIfExists('formation_tech_report');
    }
}
