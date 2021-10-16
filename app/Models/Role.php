<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    public const table = 'role';
    public const table_as = 'account as rl';

    protected $table = 'role';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'description',
    ];

    public function accounts () : BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'account_role',
                                    'id_role', 'id_account');
    }
}
