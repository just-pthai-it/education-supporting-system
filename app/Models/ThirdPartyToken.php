<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirdPartyToken extends Model
{
    use HasFactory, Filterable;

    public const TABLE    = 'third_party_tokens';
    public const TABLE_AS = 'third_party_tokens as tats';

    protected $table = 'third_party_tokens';

    protected $fillable = [
        'id',
        'id_account',
        'google_token',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'google_token' => 'array',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }
}
