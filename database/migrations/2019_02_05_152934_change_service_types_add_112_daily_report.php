<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeServiceTypesAdd112DailyReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->boolean('report112_daily')->nullable()->after('id');
        });

        foreach (['ЦМК', 'ГУ РОСО', 'Служба спасения-109 г. Алматы', 'Өрт сөндіруші', 'РГП Казавиаспас'] as $item) {
            $record = \App\Models\ServiceType::where('name', $item)->first();
            if($record) {
                $record->report112_daily = true;
                $record->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->dropColumn(['report112_daily']);
        });
    }
}
