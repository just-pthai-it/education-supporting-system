<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataVersionStudent extends Model
{
    use HasFactory, Filterable;

    public const table = 'data_version_students';
    public const table_as = 'data_version_students as dvss';

    protected $table = 'data_version_students';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'schedule',
        'exam_schedule',
        'notification',
        'module_score',
        'created_at',
        'updated_at',
    ];

    public function student () : BelongsTo
    {
        return $this->belongsTo(Student::class, 'id', 'id');
    }
}
