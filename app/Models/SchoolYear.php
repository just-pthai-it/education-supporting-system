<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    public const table = 'school_years';
    public const table_as = 'school_years as sys';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
    ];

    public function terms () : HasMany
    {
        return $this->hasMany(Term::class, 'id_school_year', 'id');
    }
}
