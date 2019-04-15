<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\User::firstOrCreate([
            'name' => 'Уведомления',
            'email' => 'notifications@localhost.net',
            'password' => bcrypt('password8')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\User::where('email', '=', 'notifications@localhost.net')->first()->delete();
    }
}
