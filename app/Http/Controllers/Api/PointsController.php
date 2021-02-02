<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PointsController extends Controller
{

    public function socs()
    {
        return \App\Soc::all();
    }
    
    public function meds()
    {
        return \App\Med::all();
    }
    
    public function schools()
    {
        return \App\School::all();
    }
    
    
    public function finances()
    {
        return \App\Finance::all();
    }
    
    public function livingZones()
    {
        return \App\LivingZone::all();
    }
    
    public function nature()
    {
        return \App\Nature::all();
    }
    
    public function techs()
    {
        return \App\Tech::all();
    }

    
}
