<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDailyReportPersonsAddReportType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_report_persons', function (Blueprint $table) {
            $table->string('report_type')->after('id')->nullable();
        });

        $persons = \App\Models\DailyReportPerson::all();

        foreach ($persons as $person) {
            $person->report_type = '101_daily';
            $person->save();
        }

        $newPersons = [
            [
                'position' => 'И. о. начальника',
                'city' => 'ДЧС г. Алматы КЧС МВД РК',
                'post' => 'полковнику',
                'name' => 'Алибекову Е. А.',
                'type' => 'header',
                'report_type' => '112_daily',
            ],
            [
                'position' => 'ОД ДЧС',
                'city' => 'г. Алматы',
                'post' => 'старшина  г/з',
                'name' => 'Сейсенов Ж.О.',
                'type' => 'footer_first',
                'report_type' => '112_daily',
            ],
        ];

        foreach ($newPersons as $newPerson) {
            \App\Models\DailyReportPerson::firstOrCreate($newPerson);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_report_persons', function (Blueprint $table) {
            $table->dropColumn(['report_type']);
        });

        $newPersons = [
            [
                'position' => 'И. о. начальника',
                'city' => 'ДЧС г. Алматы КЧС МВД РК',
                'post' => 'полковнику',
                'name' => 'Алибекову Е. А.',
                'type' => 'header',
                'report_type' => '112_daily',
            ],
            [
                'position' => 'ОД ДЧС',
                'city' => 'г. Алматы',
                'post' => 'старшина  г/з',
                'name' => 'Сейсенов Ж.О.',
                'type' => 'footer_first',
                'report_type' => '112_daily',
            ],
        ];

        foreach ($newPersons as $newPerson) {
            \App\Models\DailyReportPerson::where('name', $newPerson['name'])->delete();
        }
    }
}
