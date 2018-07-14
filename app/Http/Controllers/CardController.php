<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 08.07.2018
 * Time: 18:51
 */

namespace App\Http\Controllers;


use App\Dictionary\CityArea;
use App\Dictionary\FireObject;
use App\Ticket101;
use Illuminate\Http\Request;

class CardController extends AuthorizedController
{
    public function get101(Request $request)
    {
        $tickets = Ticket101::all();
        $this->set('tickets', $tickets);
    }

    public function getAdd101(Request $request, $card_id = 0)
    {
        $gu_notify = [
            '100' => '100',
            '101' => '101',
            '102' => '102',
            '103' => '103',
            '104' => '104',
            'b01' => 'ДЧС "Байкал-01"',
            'b04' => 'ДЧС "Байкал-04"',
        ];
        $service_notify = [
            '112' => '112',
            '102' => 'ДВД 102',
            '103' => 'БСМП 103',
            '104' => 'Служба газа 104',
            'electro' => 'Э\\сеть (277-98-42)',
            'water' => 'Водоканал (274-66-66)',
            'smk' => 'ЦМК (254-63-53)'
        ];
        $ssv_out = [
            1 => 'ӨСБ - ПЧ-1',
            ' - ПЧ-2',
            ' - ПЧ-3',
            ' - ПЧ-4',
            ' - ПЧ-5',
            ' - ПЧ-6',
            'МӨСБ - СПЧ-7',
            ' - СПЧ-8',
            ' - СПЧ-9',
            ' - ПЧ-10',
            ' - СПЧ-11',
            'ӨСБ - ПЧ-12',
            'МЖ - СО',
            'МӨСБ - СПЧ-14',
            'МӨСБ - СПЧ-15',
            ' - П.16',
            ' - П. 17',
        ];
        $this->set('ssv_out', $ssv_out);
        $this->set('gu_notify', $gu_notify);
        $this->set('service_notify', $service_notify);
        $this->set('city_area', CityArea::all());
        $this->set('fire_object', FireObject::all());
        $ticket = Ticket101::findOrNew($card_id);
        $this->set('ticket', $ticket);
    }

    public function postAdd101(Request $request, $card_id = 0)
    {
        $card = Ticket101::findOrNew($card_id);
        $card->fill($request->all());
        $card->save();
        return redirect('/card/101')->with('_message', ['type' => 'success', 'text' => 'Данные успешно сохранены']);
    }
}