<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExamSchedule extends Model
{
    use HasFactory, Filterable;

    public const table = 'exam_schedules';
    public const table_as = 'exam_schedules as ess';

    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_module_class',
        'method',
        'start_at',
        'end_at',
        'number_of_students',
        'id_room',
        'note',
    ];

    protected $hidden = [
        'pivot',
    ];

    private array $filterable = [
        'start_at',
    ];

    public function filterDate (Builder $query, $values)
    {
        $query->whereBetween('start_at', explode(',', $values));
    }

    private array $sortable = [];

    public function moduleClass () : BelongsTo
    {
        return $this->belongsTo(ModuleClass::class, 'id_module_class', 'id');
    }

    public function teachers () : BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'exam_schedule_teacher',
                                    'id_exam_schedule', 'id_teacher')
                    ->withPivot(['id']);
    }

    public function rooms () : BelongsTo
    {
        return $this->belongsTo(Room::class, 'id_room', 'id');
    }
}
