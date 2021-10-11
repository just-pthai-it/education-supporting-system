<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataVersionStudent extends Model
{
    use HasFactory;

    public const table = 'data_version_student';
    public const table_as = 'data_version_student as dvs';

    protected $table = 'data_version_student';
    protected $primaryKey = 'id_student';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_student',
        'schedule',
        'notification',
        'module_score',
        'exam_schedule'
    ];

    public function student () : BelongsTo
    {
        return $this->belongsTo(Student::class, 'id_student', 'id');
    }
}
