<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $table = 'chats';

    public $fillable = ['name'];
}
