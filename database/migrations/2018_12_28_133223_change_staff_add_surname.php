<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStaffAddSurname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('surname')->nullable()->after('id');
            $table->string('patronymic')->nullable()->after('id');
        });

        $staff = \App\Models\Staff::all();
        foreach ($staff as $item) {
            $fullName = explode(' ', $item->name);
            $item->surname = $fullName[0] ?? null;
            $item->patronymic = $fullName[2] ?? null;
            $item->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'patronymic',
            ]);
        });
    }
}
