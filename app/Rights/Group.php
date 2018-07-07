<?php
namespace App\Rights;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Rights\Group
 *
 * @property int $id
 * @property string $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Right[] $rights
 * @method static \Illuminate\Database\Query\Builder|\App\Rights\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rights\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rights\Group whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rights\Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model
{
    protected $table = 'right_groups';
    protected $fillable = ['title'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rights() {
        return $this->hasMany(\App\Right::class, 'group_id', 'id');
    }
}