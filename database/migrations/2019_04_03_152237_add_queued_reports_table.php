<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQueuedReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queued_reports', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('report_type_id')->nullable();
            $table->foreign('report_type_id')
                ->references('id')
                ->on('report_types')
                ->onDelete('cascade');

            $table->unsignedInteger('queue_status_id')->nullable();
            $table->foreign('queue_status_id')
                ->references('id')
                ->on('queue_statuses')
                ->onDelete('cascade');

            $table->string('file_path')->nullable();

            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->json('report_data')->nullable();

            $table->integer('attempts')->default(0);
            $table->text('error_text')->nullable();

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
        Schema::dropIfExists('queued_reports');
    }
}
