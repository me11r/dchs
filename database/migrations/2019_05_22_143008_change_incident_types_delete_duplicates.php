<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIncidentTypesDeleteDuplicates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $cards112 = \App\Models\Card112\Card112::where('incident_type_id', 43)
            ->orWhere('additional_incident_type_id', 43)
            ->update(['incident_type_id' => 42, 'additional_incident_type_id' => 42]);

        $cards101 = \App\Ticket101::where('pre_information_id', 43)
            ->withTrashed()
            ->update(['pre_information_id' => 42]);


        $incidentType = \App\Models\IncidentType::where('id', 43)->forceDelete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
