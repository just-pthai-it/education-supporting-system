<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory, Filterable;

    public const table = 'schedule';
    public const table_as = 'schedule as sdu';

    protected $table = 'schedule';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_module_class',
        'date',
        'shift',
        'id_room',
        'note',
        'deleted_at',
    ];

    private array $filterable = [
        'date',
        'shift',
    ];

    private array $sortable = [];

    public function moduleClass () : BelongsTo
    {
        return $this->belongsTo(ModuleClass::class, 'id_module_class', 'id');
    }

    public function rooms () : BelongsTo
    {
        return $this->belongsTo(Room::class, 'id_room', 'id');
    }

    public function fixedSchedules () : HasMany
    {
        return $this->hasMany(FixedSchedule::class, 'id_schedule', 'id');
    }
}
