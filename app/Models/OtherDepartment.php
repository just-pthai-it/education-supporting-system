<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'other_department_name',
        'email',
        'phone_number',
        'address',
        'id_account'
    ];

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }
}
