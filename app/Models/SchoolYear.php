<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolYear extends Model
{
    public const table = 'school_year';
    public const table_as = 'school_year as sy';

    use HasFactory;

    protected $table = 'school_year';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'school_year',
    ];

    public function studySessions() : HasMany
    {
        return $this->hasMany(StudySession::class, 'id_school_year', 'id');
    }
}
