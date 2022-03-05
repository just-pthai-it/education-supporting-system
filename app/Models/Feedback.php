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

    protected $table = 'feedback';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'content',
        'feedback_type',
        'create_at',
        'is_bug',
        'id_account',
    ];

    private array $filterable = [
        'feedback_type',
        'is_bug',
    ];

    private array $sortable = [
        'id',
    ];

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }
}
