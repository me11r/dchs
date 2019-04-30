<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMudflowProtectionsChangeDateToDatetime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mudflow_protections', function (Blueprint $table) {
            $table->datetime('date')->change();
        });

        foreach (\App\Models\MudflowProtection::all() as $item) {
           $block = \App\MudflowProtectionBlock::firstOrCreate([
                'date' => \Carbon\Carbon::parse($item->date)->format('Y-m-d'),
            ]);

            $item->block_id = $block->id;
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
        Schema::table('mudflow_protections', function (Blueprint $table) {
            $table->date('date')->change();
        });
    }
}
