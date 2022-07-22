<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory, Filterable;
    public const TABLE = 'admins';
    public const TABLE_AS = 'admins as adms';

    protected $table = 'admins';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function account () : MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }
}
