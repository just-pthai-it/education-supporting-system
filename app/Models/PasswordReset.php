<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $primaryKey = 'email';
    protected $keyType    = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'email',
        'token',
        'created_at',
        'expired_at',
    ];

//    protected $casts = [
//        'created_at' => 'datetime',
//        'expired_at' => 'datetime',
//    ];
}
