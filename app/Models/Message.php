<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $table = 'messages';

    public $fillable = ['datetime', 'text', 'chat_id', 'nickname_id'];

    public function nickname()
    {
        return $this->belongsTo(Nickname::class, 'nickname_id', 'id');
    }
}
