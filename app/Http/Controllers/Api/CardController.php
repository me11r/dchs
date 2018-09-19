<?php

namespace App\Http\Controllers\Api;

use App\Models\FormationPersonsItem;
use App\Models\FormationTechItem;
use App\Models\Ticket101\Ticket101OtherRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CardController extends Controller
{
    public function delete()
    {

    }

    public function createOtherRecord101card(Request $request)
    {
        $data = $request->all();
        $resp = [];
        if($request->records){
            foreach ($request->records as $record) {
                Ticket101OtherRecord::updateOrCreate(['id' => $record['id']],[
                    'ticket101_id' => $request->ticket_id,
                    'time' => $record['time'],
                    'comment' => $record['comment'],
                    'trunk_id' => $record['trunk_id'],
                    'count' => $record['count'],
                    'square' => $record['square'],
                ]);
            }
        }
        else{
            $resp = Ticket101OtherRecord::create([
                'ticket101_id' => $request->ticket_id,
                'time' => '00.00',
                'comment' => '',
                'trunk_id' => 1,
                'count' => 0,
                'square' => 0,
            ]);
        }

        return response()->json($resp);
    }
}
