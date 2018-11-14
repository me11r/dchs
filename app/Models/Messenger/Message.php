<?php
declare(strict_types=1);

namespace App\Models\Messenger;


use Illuminate\Database\Query\Builder;

final /**
 * App\Models\Messenger\Message
 *
 * @property int $id
 * @property int $sender_id
 * @property int $reciever_id
 * @property string $message_type
 * @property int|null $file_id
 * @property string|null $message
 * @property int $is_viewed
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message unread()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereIsViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereMessageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereRecieverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Message extends \Eloquent
{
    protected $table = 'messenger_messages';
    protected $fillable = ['message', 'file_id', 'sender_id', 'reciever_id', 'is_viewed', 'message_type'];

    /**
     * @param $query Builder
     * @return Builder
     */
    public function scopeUnread($query)
    {
        return $query->where('is_viewed', false);
    }
}
