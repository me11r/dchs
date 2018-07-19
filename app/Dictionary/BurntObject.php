<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 19.07.2018
 * Time: 21:07
 */

namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;

class BurntObject extends Model
{
    protected $table = 'dict_burn_object';
    protected $guarded = ['id'];
}