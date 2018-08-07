<?php


namespace App\Formation;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Formation\Operations
 *
 * @property int $id
 * @property int $formation_savers_report_id
 * @property string|null $event_date
 * @property string|null $comment
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Operations whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Operations whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Operations whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Operations whereFormationSaversReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Operations whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formation\Operations whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Operations extends Model
{
    protected $table = 'formation_savers_operations';
    protected $guarded = ['id'];
}