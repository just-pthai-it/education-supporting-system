<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AcademicYear extends Model
{
    use HasFactory;

    public const table = 'academic_years';
    public const table_as = 'academic_years as sys';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'id_training_type',
    ];

    public function classes () : HasMany
    {
        return $this->hasMany(Class_::class, 'id_academic_year', 'id');
    }

    public function trainingType () : BelongsTo
    {
        return $this->belongsTo(TrainingType::class, 'id_training_type', 'id');
    }

    public function majors () : BelongsToMany
    {
        return $this->belongsToMany(Major::class, 'academic_year_major', 'id_academic_year',
                                    'id_major')->withPivot(['id_curriculum']);
    }
}
