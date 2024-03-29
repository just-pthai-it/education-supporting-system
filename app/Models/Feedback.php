<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory, Filterable;

    public const TABLE    = 'feedback';
    public const TABLE_AS = 'feedback as fb';

    protected $table      = 'feedback';
    public    $timestamps = false;

    protected $fillable = [
        'id',
        'data',
        'type',
        'is_bug',
        'id_account',
        'created_at',
    ];

    private array $filterable = [
        'type',
        'is_bug',
    ];

    private array $sortable = [
        'id',
    ];

    protected $casts = [
        'data'       => 'array',
        'created_at' => 'datetime',
    ];

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }
}
