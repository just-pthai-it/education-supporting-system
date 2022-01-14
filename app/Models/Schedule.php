<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Schedule extends Model
{
    use HasFactory;

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
    ];

    public function moduleClass () : BelongsTo
    {
        return $this->belongsTo(ModuleClass::class, 'id_module_class', 'id');
    }

    public function rooms () : BelongsTo
    {
        return $this->belongsTo(Room::class, 'id_room', 'id');
    }

    public function fixedSchedules () : hasOne
    {
        return $this->hasOne(FixedSchedule::class, 'id_schedule', 'id');
    }
}
