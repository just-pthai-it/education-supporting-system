<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudySession extends Model
{
    public const table = 'study_session';
    public const table_as = 'study_session as ss';

    use HasFactory;

    protected $table = 'study_session';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'study_session',
        'id_school_year',
    ];

    public function schoolYear () : BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'id_school_year', 'id');
    }

    public function moduleClasses () : HasMany
    {
        return $this->hasMany(ModuleClass::class, 'id_study_session', 'id');
    }
}
