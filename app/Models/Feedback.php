<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

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
        'id_account',
    ];

    public function roles () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }
}
