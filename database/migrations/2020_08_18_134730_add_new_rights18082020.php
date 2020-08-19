<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewRights18082020 extends Migration
{
    protected $newRights = [
        'CAN_VIEW_TRIP_PLAN',
        'CAN_CHANGE_TRIP_PLAN',
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        (new RightsSeeder())->run();

        $newRights = \App\Right::whereIn('name', $this->newRights)->get();
        $rolesWithMessengerPermissions = \App\Role::whereHas('rights', function ($right) {
            $right->where('rights.name', 'CAN_SEE_TRIP_PLAN');
        })->get();

        foreach ($rolesWithMessengerPermissions as $rolesWithMessengerPermission) {
            foreach ($newRights as $newRight) {
                \Illuminate\Support\Facades\DB::table('role_to_rights')->insert([
                    'role_id' => $rolesWithMessengerPermission->id,
                    'right_id' => $newRight->id,
                ]);
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
        \App\Right::whereIn('name', $this->newRights)->delete();
    }
}
