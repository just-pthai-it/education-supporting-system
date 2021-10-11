<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataVersionTeacher extends Model
{
    use HasFactory;

    public const table = 'data_version_teacher';
    public const table_as = 'data_version_teacher as dvt';

    protected $table = 'data_version_teacher';
    protected $primaryKey = 'id_teacher';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_teacher',
        'schedule',
        'notification',
    ];

    public function teacher () : BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'id_teacher', 'id');
    }
}
