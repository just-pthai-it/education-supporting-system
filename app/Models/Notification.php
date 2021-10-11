<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends Model
{
    use HasFactory;

    public const table = 'notification';
    public const table_as = 'notification as noti';

    protected $table = 'notification';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'content',
        'type',
        'id_sender',
        'time_create',
        'time_start',
        'time_end',
        'id_delete'
    ];

    protected $hidden = [
        'is_delete',
        'pivot',
    ];

    public function sender () : BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_sender', 'id');
    }

    public function accounts () : BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'notification_account',
                                    'id_notification', 'id_account');
    }
}
