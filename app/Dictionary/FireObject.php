<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 13:43
 */

namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;

class FireObject extends Model
{
    protected $table = 'dict_fire_object';
    protected $guarded = ['id'];
}