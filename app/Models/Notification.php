<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Pivots\AccountNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends Model
{
    use HasFactory, Filterable;

    public const TABLE    = 'notifications';
    public const TABLE_AS = 'notifications as notis';

    protected $table      = 'notifications';
    public    $timestamps = false;

    protected $fillable = [
        'id',
        'data',
        'type',
        'id_account',
        'action',
        'created_at',
        'updated_at',
    ];

    protected array $sortable = [
        'id',
        'created_at',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'data'                   => 'array',
        'created_at'             => 'datetime',
        'updated_at'             => 'datetime',
        'accounts.pivot.read_at' => 'datetime',
    ];

    public function filterMilestone (Builder $query, string $value)
    {
        $query->where('created_at', '<', $value);
    }

    public function tags () : BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'notification_tag', 'id_notification', 'id_tag');
    }

    public function account () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_account', 'id');
    }

    public function accounts () : BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'account_notification',
                                    'id_notification', 'id_account')
                    ->withPivot(['id', 'read_at'])->using(AccountNotification::class);
    }
}
