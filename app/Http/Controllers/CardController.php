<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 08.07.2018
 * Time: 18:51
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class CardController extends AuthorizedController
{
    public function get101(Request $request)
    {

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
            'cmk' => 'ЦМК (254-63-53)'
        ];
        $this->set('gu_notify', $gu_notify);
        $this->set('service_notify', $service_notify);
    }

    public function postAdd101(Request $request, $card_id = 0)
    {

    }
}