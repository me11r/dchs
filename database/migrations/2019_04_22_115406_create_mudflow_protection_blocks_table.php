<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMudflowProtectionBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mudflow_protection_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->index();
            $table->text('text_header')->nullable();
            $table->text('text_footer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mudflow_protection_blocks');
    }
}
