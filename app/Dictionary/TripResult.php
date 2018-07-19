<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 19.07.2018
 * Time: 21:11
 */

namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;

class TripResult extends Model
{
    protected $table = 'dict_trip_result';
    protected $guarded = ['id'];
}