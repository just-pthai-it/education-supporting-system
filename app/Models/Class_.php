<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Class_ extends Model
{
    use HasFactory, Filterable;

    public const table = 'classes';
    public const table_as = 'classes as cls';

    protected $table = 'classes';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'id_academic_year',
        'id_faculty',
    ];

    private array $filterable = [
        'id_academic_year',
        'id_faculty',
    ];

    private array $sortable = [
        'id',
        'id_academic_year',
        'id_faculty',
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
