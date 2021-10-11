<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use HasFactory;

    public const table = 'academic_year';
    public const table_as = 'academic_year as sy';

    use HasFactory;

    protected $table = 'academic_year';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'academic_year',
    ];

    public function classes() : HasMany
    {
        return $this->hasMany(Class_::class, 'id_academic_year', 'id');
    }
}
