<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Major extends Model
{
    use HasFactory;

    public const table = 'majors';
    public const table_as = 'majors as mjs';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'id_faculty',
    ];

    public function faculty () : BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'id_faculty', 'id');
    }

    public function academicYears () : BelongsToMany
    {
        return $this->belongsToMany(AcademicYear::class, 'academic_year_major', 'id_major',
                                    'id_academic_year')->withPivot(['id_curriculum']);
    }
}
