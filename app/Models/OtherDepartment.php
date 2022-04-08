<?php

namespace App\Models;

use App\Helpers\GFunction;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDepartment extends Model
{
    use HasFactory;

    public const table = 'other_departments';
    public const table_as = 'other_departments as ods';

    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'address',
    ];

    protected $hidden = [
        'uuid',
    ];

    public function account () : MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }
}
