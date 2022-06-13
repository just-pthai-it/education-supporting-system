<?php

namespace App\Models;

use App\Helpers\GFunction;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory, Filterable;

    public const TABLE = 'faculties';
    public const TABLE_AS = 'faculties as facs';

    protected $table = 'faculties';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'address',
    ];

    private array $filterable = [];

    private array $sortable = [];

    public function departments () : HasMany
    {
        return $this->hasMany(Department::class, 'id_faculty', 'id');
    }

    public function classes () : HasMany
    {
        return $this->hasMany(Class_::class, 'id_faculty', 'id');
    }

    public function majors () : HasMany
    {
        return $this->hasMany(Major::class, 'id_faculty', 'id');
    }
}
