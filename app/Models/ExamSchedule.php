<?php

namespace App\Models;

use App\Models\Traits\Filterable;
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

    private array $sortable = [];

    public function moduleClass () : BelongsTo
    {
        return $this->belongsTo(ModuleClass::class, 'id', 'id');
    }

    public function teachers () : BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'exam_schedule_teacher',
                                    'id_exam_schedule', 'id_teacher')
                    ->withPivot(['position']);
    }
}
