<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    use HasFactory;

    public const table = 'device';
    public const table_as = 'device as d';

    protected $table = 'device';
    protected $primaryKey = 'device_token';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'device_token',
        'id_account',
        'last_use'
    ];

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }
}
