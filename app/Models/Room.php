<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    public const table = 'room';
    public const table_as = 'room as r';

    protected $table = 'room';
    protected $primaryKey = 'id';
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
