<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FixedSchedule extends Model
{
    use HasFactory, Filterable;

    public const table = 'fixed_schedules';
    public const table_as = 'fixed_schedules as fss';

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
        'reason',
        'reason_deny',
        'status',
        'created_at',
        'updated_at',
        'accepted_at',
        'set_room_at',
    ];

    private array $filterable = [
        'status',
    ];

    private array $sortable = [
        'id',
        'old_date',
        'old_shift',
        'old_id_room',
    ];

    public function filterDate (Builder $query, $values)
    {
        $query->where(function ($query) use ($values)
        {
            $query->whereBetween('old_date', explode(',', $values))
                  ->orWhereBetween('new_date', explode(',', $values));
        });
    }

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
