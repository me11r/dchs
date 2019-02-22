<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddCustomCreatedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->timestamp('custom_created_at')->after('created_at')->nullable();
        });

        $cards = App\Models\Card112\Card112::all();

        foreach ($cards as $card) {
            $card->custom_created_at = $card->created_at;
            $card->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->dropColumn(['custom_created_at']);
        });
    }
}
