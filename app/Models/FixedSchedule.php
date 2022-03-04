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
        'reason',
        'time_accept',
        'time_set_room',
        'status',
        'reason_deny',
        'id_notification',
    ];

    private array $filterable = [
        'status',
    ];

    private array $sortable = [
        'id',
    ];

    public function filterDateRange (Builder $query, $values)
    {
        $query->where(function ($query) use ($values)
        {
            $query->whereBetween('old_date', explode(',', $values))
                  ->orWhereBetween('new_date', explode(',', $values));
        });
    }

    public function scopeStatus ($query, $status)
    {
        if ($status == 'all')
        {
            return $query;
        }

        $values = explode(',', $status);
        if (count($values) == 1)
        {
            return $query->where('status', '=', $values[0]);
        }
        else
        {
            return $query->whereIn('status', $values);
        }
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
