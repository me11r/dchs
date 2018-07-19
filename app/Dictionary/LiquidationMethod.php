<?php


namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;

class LiquidationMethod extends Model
{
    protected $table = 'dict_liquidation_method';
    protected $guarded = ['id'];
}