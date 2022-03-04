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

    public const table = 'exam_schedule';
    public const table_as = 'exam_schedule as es';

    protected $table = 'exam_schedule';
    protected $primaryKey = 'id_module_class';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
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

    private array $filterable = [
        'time_start',
    ];

    private array $sortable = [];

    public function moduleClass () : BelongsTo
    {
        return $this->belongsTo(ModuleClass::class, 'id_module_class', 'id');
    }

    public function teachers () : BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'exam_schedule_teacher',
                                    'id_module_class', 'id_teacher')
                    ->withPivot('position');
    }
}
