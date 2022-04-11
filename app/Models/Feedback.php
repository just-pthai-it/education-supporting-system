<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory, Filterable;

    public const table = 'feedback';
    public const table_as = 'feedback as fb';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'data',
        'type',
        'is_bug',
        'id_account',
        'created_at',
    ];

    protected array $filterable = [
        'type',
        'is_bug',
    ];

    protected array $sortable = [
        'id',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }
}
