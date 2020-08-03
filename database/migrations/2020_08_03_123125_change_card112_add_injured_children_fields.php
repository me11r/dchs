<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddInjuredChildrenFields extends Migration
{
    private $newFields = [
        'injured_children',
        'dead_children',
        'evacuated_children',
        'hospitalized_children',
        'injured_hard_children',
        'poisoned_children',
        'saved_children',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            foreach ($this->newFields as $newField) {
                $table->integer($newField)->nullable()->after('resources');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('card_112', function (Blueprint $table) {
            $table->dropColumn($this->newFields);
        });
    }
}
