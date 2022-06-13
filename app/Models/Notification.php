<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends Model
{
    use HasFactory;

    public const TABLE = 'notifications';
    public const TABLE_AS = 'notifications as notis';

    protected $table = 'notifications';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'data',
        'type',
        'created_at',
        'updated_at',
        'id_account',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'data' => 'array',
    ];

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
        return $this->belongsToMany(Account::class, 'account_notification', 'id_notification',
                                    'id_account')->withPivot(['read_at']);
    }
}
