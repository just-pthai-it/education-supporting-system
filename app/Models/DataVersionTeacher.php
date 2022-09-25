<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataVersionTeacher extends Model
{
    use HasFactory;

    public const TABLE    = 'data_version_teachers';
    public const TABLE_AS = 'data_version_teachers as dvts';

    protected $table      = 'data_version_teachers';
    protected $keyType    = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'id',
        'schedule',
        'exam_schedule',
        'notification',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function teacher () : BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'id', 'id');
    }
}
