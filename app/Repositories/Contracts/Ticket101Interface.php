<?php

namespace App\Repositories\Contracts;


interface Ticket101Interface
{

    public function getDaily($from, $to);

    public function getDailyDrill($from, $to);


}