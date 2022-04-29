<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FcmRegistrationToken extends Model
{
    use HasFactory;

    public const table = 'fcm_registration_tokens';
    public const table_as = 'fcm_registration_tokens as frts';

    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_account',
        'created_at',
        'updated_at',
    ];

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }
}
