<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'faculty_name',
        'email',
        'phone_number',
        'address',
        'id_account'
    ];

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }

    public function departments () : HasMany
    {
        return $this->hasMany(Department::class, 'id_faculty', 'id');
    }

    public function classes () : HasMany
    {
        return $this->hasMany(Class_::class, 'id_faculty', 'id');
    }
}
