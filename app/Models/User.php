<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'accounts';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'username',
        'password',
        'qldt_password',
        'email',
        'phone',
        'id_role',
        'accountable_type',
        'accountable_id',
        'uuid',
    ];

    protected $hidden = [
        'id',
        'username',
        'email',
        'password',
        'qldt_password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
