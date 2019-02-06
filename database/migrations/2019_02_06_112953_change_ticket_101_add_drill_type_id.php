<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101AddDrillTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {

            $table->unsignedInteger('drill_type_id')->nullable()->after('id');

            $table->foreign('drill_type_id')
                ->references('id')
                ->on('drill_types')
                ->onDelete('cascade');

            /*$cards = \App\Ticket101::whereIn('form_type_drill',[
                'РКШУ',
                'ТСУ',
                'ПТУ',
                'ПТЗ',
                'ТДК',
                'Учения'
            ])->get();

            foreach ($cards as $card) {
                $drillType = \App\DrillType::where('name', $card->form_type_drill)->first();
                $card->drill_type_id = $drillType->id;
                $card->save();
            }*/
        });

        Schema::table('ticket101', function (Blueprint $table) {
            $table->dropColumn(['drill_type','form_type_drill']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->string('drill_type')->nullable()->after('id');
            $table->string('form_type_drill')->nullable()->after('id');

            $table->dropForeign(['drill_type_id']);
            $table->dropColumn(['drill_type_id']);
        });
    }
}
