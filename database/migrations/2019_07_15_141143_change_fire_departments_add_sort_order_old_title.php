<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFireDepartmentsAddSortOrderOldTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_departments', function (Blueprint $table) {
            $table->string('old_title')->index()->nullable()->after('title');
            $table->unsignedInteger('sort_order')->index()->nullable()->default(100)->after('id');
        });

        $titles = [
            ['title' => 'СПЧ-1','old_title' => 'ПЧ-6',],
            ['title' => 'СПЧ-2','old_title' => 'СПЧ-15',],
            ['title' => 'СПЧ-3','old_title' => 'СПЧ-14',],
            ['title' => 'СПЧ-4','old_title' => 'СПЧ-11',],
            ['title' => 'СПЧ-5','old_title' => 'СПЧ-8',],
            ['title' => 'СПЧ-6','old_title' => 'СПЧ-7',],

            ['title' => 'ПЧ-7','old_title' => 'ПЧ-1',],
            ['title' => 'ПЧ-8','old_title' => 'ПЧ-2',],
            ['title' => 'ПЧ-9','old_title' => 'ПЧ-3',],
            ['title' => 'ПЧ-10','old_title' => null,],
            ['title' => 'ПЧ-11','old_title' => 'ПЧ-4',],
            ['title' => 'ПЧ-12','old_title' => null,],
            ['title' => 'ПЧ-13','old_title' => null,],
            ['title' => 'ПЧ-14','old_title' => 'ПЧ-5',],
            ['title' => 'ПЧ-15','old_title' => 'ПП-17',],
            ['title' => 'ПЧ-16','old_title' => 'СПЧ-9',],
            ['title' => 'ПЧ-17','old_title' => 'ПП-16',],

            ['title' => 'СО','old_title' => null,],
            ['title' => 'ОД','old_title' => null,],
            ['title' => 'ЦОУСС','old_title' => null,],
            ['title' => 'ИПЛ','old_title' => null,],
        ];

        foreach ($titles as $iterator => $item) {
            $sortOrder = ++$iterator;
            \App\FireDepartment::where(function ($q) use ($item, $sortOrder) {
                $q->where('title', $item['title']);
            })->update([
                'sort_order' => $sortOrder,
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
        Schema::table('fire_departments', function (Blueprint $table) {
            $table->dropIndex(['old_title']);
            $table->dropIndex(['sort_order']);
            $table->dropColumn(['old_title']);
            $table->dropColumn(['sort_order']);
        });
    }
}
