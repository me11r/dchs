<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 13:30
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    protected $table = 'dictionaries';
    protected $guarded = ['id'];
}