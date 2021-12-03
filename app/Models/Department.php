<?php

namespace App\Models;

use App\Helpers\GFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    public const table = 'department';
    public const table_as = 'department as dep';

    protected $table = 'department';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'address',
        'id_faculty',
        'uuid'
    ];

    protected $hidden = [
        'uuid',
    ];

    private array $column = [
        'name',
        'address',
        'id_faculty',
    ];

    public function scopeWithUuid ($query, ...$e)
    {
        if (empty($e))
        {
            return $query->select(GFunction::uuidFromBin('uuid'), ...$this->column);
        }

        return $query->select(GFunction::uuidFromBin('uuid'), ...$e);
    }

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }

    public function faculty () : BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'id_faculty', 'id');
    }

    public function modules () : HasMany
    {
        return $this->hasMany(Module::class, 'id_department', 'id');
    }
}
