<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    public const TABLE = 'tags';
    public const TABLE_AS = 'tags';

    protected $table = 'tags';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'is_optional',
        'is_permanent',
    ];

    protected $hidden = [];

    public function notifications () : BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'notification_tag', 'id_tag',
                                    'id_notification');
    }

    public function accounts () : BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'account_tag', 'id_tag', 'id_account');
    }

}
