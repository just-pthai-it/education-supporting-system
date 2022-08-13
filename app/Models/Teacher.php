<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory, Filterable;

    public const TABLE = 'teachers';
    public const TABLE_AS = 'teachers as teas';

    protected $table = 'teachers';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'is_female',
        'birth',
        'university_teacher_degree',
        'is_head_of_department',
        'is_head_of_faculty',
        'is_active',
        'id_department',
        'deleted_at',
    ];

    protected $hidden = [
        'is_delete',
        'pivot',
    ];

    private array $filterable = [
        'id_department',
    ];

    private array $sortable = [];

    public function account () : MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    public function moduleClasses () : HasMany
    {
        return $this->hasMany(ModuleClass::class, 'id_teacher', 'id');
    }

    public function department () : BelongsTo
    {
        return $this->belongsTo(Department::class, 'id_department', 'id');
    }

    public function examSchedules () : BelongsToMany
    {
        return $this->belongsToMany(ExamSchedule::class, 'exam_schedule_teacher',
                                    'id_teacher', 'id_exam_schedule')
                    ->withPivot('id', 'note');
    }
}
