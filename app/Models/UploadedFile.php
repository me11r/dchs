<?php


namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\UploadedFile
 *
 * @property int $id
 * @property string $filename
 * @property string $filepath
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UploadedFile onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadedFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadedFile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadedFile whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadedFile whereFilepath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadedFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadedFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UploadedFile withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UploadedFile withoutTrashed()
 * @mixin \Eloquent
 * @property string $mime
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadedFile whereMime($value)
 * @property int $size
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UploadedFile whereSize($value)
 */
class UploadedFile extends \Eloquent
{
    use SoftDeletes;
    protected $table = 'file_uploads';
    protected $fillable = ['filename', 'filepath', 'mime', 'size'];
}
