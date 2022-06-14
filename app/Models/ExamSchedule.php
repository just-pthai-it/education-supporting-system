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

    public const TABLE = 'exam_schedules';
    public const TABLE_AS = 'exam_schedules as ess';

    protected $table = 'exam_schedules';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_module_class',
        'method',
        'start_at',
        'end_at',
        'number_of_students',
        'id_room',
    ];

    protected $hidden = [
        'pivot',
    ];

    private array $filterable = [
        'start_at',
    ];

    private array $sortable = [
        'id',
        'start_at',
    ];

    public function filterDate (Builder $query, $values)
    {
        $values = explode(',', $values['between']);
        if (count($values) == 1)
        {
            $query->where('time_start', '=', $values[0]);
        }
        else
        {
            $query->whereBetween('start_at',
                                 ["{$values[0]} 00:00:00.000", "{$values[1]} 23:59:59.000"]);
        }
    }

    public function moduleClass () : BelongsTo
    {
        return $this->belongsTo(ModuleClass::class, 'id_module_class', 'id');
    }

    public function teachers () : BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'exam_schedule_teacher',
                                    'id_exam_schedule', 'id_teacher')
                    ->withPivot(['id', 'note']);
    }

    public function rooms () : BelongsTo
    {
        return $this->belongsTo(Room::class, 'id_room', 'id');
    }
}
