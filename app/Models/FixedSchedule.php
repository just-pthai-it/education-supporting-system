<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FixedSchedule extends Model
{
    use HasFactory;

    public const table = 'fixed_schedule';
    public const table_as = 'fixed_schedule as fs';

    protected $table = 'fixed_schedule';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_schedule',
        'new_date',
        'new_shift',
        'new_id_room',
        'old_date',
        'old_shift',
        'old_id_room',
        'time_request',
        'time_accept',
        'time_set_room',
        'status',
    ];

    public function schedule () : BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'id_schedule', 'id');
    }

    public function oldRoom () : BelongsTo
    {
        return $this->belongsTo(Room::class, 'old_id_room', 'id');
    }

    public function newRoom () : BelongsTo
    {
        return $this->belongsTo(Room::class, 'new_id_room', 'id');
    }
}
