<?php

namespace App\Models;

use App\Helpers\GFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory;

    public const table = 'faculty';
    public const table_as = 'faculty as fac';

    protected $table = 'faculty';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'address',
        'uuid'
    ];

    protected $hidden = [
        'uuid',
    ];

    private array $column = [
        'name',
        'address',
    ];

    public function scopeWithUuid ($query, ...$e)
    {
        if (empty($e))
        {
            return $query->select(GFunction::uuidFromBin('uuid'), ...$this->column);
        }

        return $query->select(GFunction::uuidFromBin('uuid'), ...$e);
    }

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
