<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'name',
    ];

    public function permissions () : BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'id_role',
                                    'id_permission')
                    ->where('is_granted', '=', 1);
    }

    public function accounts () : HasMany
    {
        return $this->hasMany(Account::class, 'id_role', 'id');
    }
}
