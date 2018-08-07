<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property int $chat_id
 * @property int $nickname_id
 * @property string $datetime
 * @property string $text
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Nickname $nickname
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereNicknameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    public $table = 'messages';

    public $fillable = ['datetime', 'text', 'chat_id', 'nickname_id'];

    public function nickname()
    {
        return $this->belongsTo(Nickname::class, 'nickname_id', 'id');
    }
}
