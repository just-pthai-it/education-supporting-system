<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataVersionTeacher extends Model
{
    use HasFactory;

    public const table = 'data_version_teachers';
    public const table_as = 'data_version_teachers as dvts';

    protected $table = 'data_version_teachers';
    protected $primaryKey = 'id_teacher';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_teacher',
        'schedule',
        'exam_schedule',
        'notification',
        'created_at',
        'updated_at',
    ];

    public function teacher () : BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'id_teacher', 'id');
    }
}
