<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeServiceTypesAddInCard101 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->boolean('in_card101')->after('id')->nullable();
            $table->boolean('in_card112')->after('id')->nullable();
            $table->integer('sort_order')->after('id')->default(10);
        });

        $dict = \App\Dictionary::name('Типы служб')->update(['url' => '/dictionaries/service-types']);

        $dict = \App\Models\ServiceType::select("*")
            ->update([
                'in_card101' => true,
                'in_card112' => true,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->dropColumn([
                'in_card101',
                'in_card112',
            ]);
        });

        $dict = \App\Dictionary::name('Типы служб')->update(['url' => null]);

    }
}
