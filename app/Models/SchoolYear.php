<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    public const TABLE = 'school_years';
    public const TABLE_AS = 'school_years as sys';

    protected $table = 'school_years';
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
