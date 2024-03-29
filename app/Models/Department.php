<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    public const TABLE    = 'departments';
    public const TABLE_AS = 'departments as deps';

    protected $table      = 'departments';
    protected $keyType    = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'address',
        'id_faculty',
    ];

    public function faculty () : BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'id_faculty', 'id');
    }

    public function modules () : HasMany
    {
        return $this->hasMany(Module::class, 'id_department', 'id');
    }

    public function teachers () : HasMany
    {
        return $this->hasMany(Teacher::class, 'id_department', 'id');
    }
}
