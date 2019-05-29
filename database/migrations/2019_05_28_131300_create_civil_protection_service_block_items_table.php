<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCivilProtectionServiceBlockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('civil_protection_service_block_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('cp_service_id');
            $table->foreign('cp_service_id')
                ->references('id')
                ->on('civil_protection_services')
                ->onDelete('cascade');

            $table->unsignedInteger('cp_service_block_id');
            $table->foreign('cp_service_block_id')
                ->references('id')
                ->on('civil_protection_service_blocks')
                ->onDelete('cascade');

            $table->longText('position')->nullable();
            $table->longText('name')->nullable();
            $table->longText('contacts')->nullable();
            $table->longText('inventory1')->nullable();
            $table->longText('inventory2')->nullable();
            $table->longText('inventory3')->nullable();

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
        Schema::dropIfExists('civil_protection_service_block_items');
    }
}
