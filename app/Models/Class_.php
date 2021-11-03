<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Class_ extends Model
{
    use HasFactory;

    public const table = 'class';
    public const table_as = 'class as cl';

    protected $table = 'class';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_academic_year',
        'name',
        'id_faculty',
        'id_training_type',
    ];

    public function students () : HasMany
    {
        return $this->hasMany(Student::class, 'id_class', 'id');
    }

    public function academicYear () : BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'id_academic_year', 'id');
    }

    public function faculty () : BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'id_faculty', 'id');
    }
}
