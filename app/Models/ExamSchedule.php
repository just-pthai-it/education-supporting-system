<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExamSchedule extends Model
{
    use HasFactory;

    public const table = 'exam_schedule';
    public const table_as = 'exam_schedule as es';

    protected $table = 'exam_schedule';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_module_class',
        'method',
        'time_start',
        'time_end',
        'id_room',
        'note',
    ];

    protected $hidden = [
        'pivot',
    ];

    public function moduleClass () : BelongsTo
    {
        return $this->belongsTo(ModuleClass::class, 'id_module_class', 'id');
    }

    public function teachers () : BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'exam_schedule_teacher',
                                    'id_exam_schedule', 'id_teacher')
                    ->withPivot('position');
    }
}
