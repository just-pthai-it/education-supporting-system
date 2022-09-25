<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FcmRegistrationToken extends Model
{
    use HasFactory;

    public const TABLE    = 'fcm_registration_tokens';
    public const TABLE_AS = 'fcm_registration_tokens as frts';

    protected $table      = 'fcm_registration_tokens';
    protected $keyType    = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'id',
        'id_account',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }
}
