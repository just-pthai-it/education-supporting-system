<?php

namespace App\Models;

use App\Helpers\GFunction;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDepartment extends Model
{
    use HasFactory;

    public const table = 'other_department';
    public const table_as = 'other_department as od';

    protected $table = 'other_department';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'address',
        'id_account'
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

    public function account () : HasOne
    {
        return $this->hasOne(Account::class, 'id_user', 'id');
    }
}
