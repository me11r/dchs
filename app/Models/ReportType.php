<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ReportType
 *
 * @mixin \Illuminate\Database\Eloquent\Model | Builder
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReportType whereUpdatedAt($value)
 */
class ReportType extends Model
{
    public $table = 'report_types';

    protected $guarded = ['id'];
}
