<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRightCanEditCheckFd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (\App\Rights\Group::find(7)) {
            \App\Right::firstOrCreate([
                'name' => 'CAN_EDIT_CHECK_FD',
                'title' => 'Проверка пожарных частей: редактирование',
                'right_group_id' => 7,
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
        \App\Right::where('name', 'CAN_EDIT_CHECK_FD')->delete();
    }
}
