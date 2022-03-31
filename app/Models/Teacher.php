<?php

namespace App\Models;

use App\Helpers\GFunction;
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

    public const table = 'teacher';
    public const table_as = 'teacher as tea';

    protected $table = 'teacher';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'is_female',
        'birth',
        'university_teacher_degree',
        'id_department',
        'schedule_data_version',
        'notification_data_version',
        'is_head_of_department',
        'is_head_of_faculty',
        'is_active',
        'deleted_at',
        'uuid',
    ];

    protected $hidden = [
        'uuid',
        'is_delete',
        'pivot',
    ];

    private array $filterable = [];

    private array $sortable = [];

    private array $column = [
        'name',
        'is_female',
        'birth',
        'university_teacher_degree',
        'id_department',
        'schedule_data_version',
        'notification_data_version',
    ];

    public function scopeWithUuid ($query, ...$e)
    {
        if (empty($e))
        {
            return $query->select([...$this->fillable, GFunction::uuidFromBin('uuid')]);
        }

        return $query->select(GFunction::uuidFromBin('uuid'), ...$e);
    }

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
                                    'id_teacher', 'id_module_class')
                    ->withPivot('position');
    }
}
