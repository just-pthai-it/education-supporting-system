<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory, Filterable;

    public const table = 'rooms';
    public const table_as = 'rooms as ros';

    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'capacity',
        'micro',
        'air_conditional',
        'projector',
    ];

    private array $filterable = [];

    private array $sortable = [];

    public function schedules () : HasMany
    {
        return $this->hasMany(Schedule::class, 'id_room', 'id');
    }

    public function fixedSchedules1 () : HasMany
    {
        return $this->hasMany(FixedSchedule::class, 'old_id_room', 'id');
    }

    public function fixedSchedules2 () : HasMany
    {
        return $this->hasMany(FixedSchedule::class, 'new_id_room', 'id');
    }
}
