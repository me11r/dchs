<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteRightCanEditCheckFd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Right::where('name', 'CAN_EDIT_CHECK_FD')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Right::firstOrCreate([
            'name' => 'CAN_EDIT_CHECK_FD',
            'title' => 'Проверка пожарных частей: редактирование',
            'right_group_id' => 7,
        ]);
    }
}
