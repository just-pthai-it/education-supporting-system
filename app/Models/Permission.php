<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    public const TABLE = 'permissions';
    public const TABLE_AS = 'permissions as pers';

    protected $table = 'permissions';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'description',
    ];

    public function roles () : BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'id_permission', 'id_role');
    }
}
