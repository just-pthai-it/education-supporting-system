<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Account extends Model
{
    use HasFactory;

    public const table = 'account';
    public const table_as = 'account as acc';

    protected $table = 'account';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'username',
        'password',
        'qldt_password',
        'email',
        'phone_number',
        'id_role',
        'accountable_type',
        'accountable_id',
        'uuid',
    ];

    protected $hidden = [
        'uuid',
    ];

    /**
     * @throws Exception
     */
    public function accountable () : MorphTo
    {
        return $this->morphTo();
    }

    public function devices () : HasMany
    {
        return $this->hasMany(Device::class, 'id_account', 'id');
    }

    public function notifications () : BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'notification_account', 'id_account',
                                    'id_notification')->withPivot(['read_at']);
    }

    public function notification () : HasOne
    {
        return $this->hasOne(Notification::class, 'id_account', 'id');
    }

    public function tags () : BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'account_tag', 'id_account', 'id_tag');
    }

    public function feedbacks () : HasMany
    {
        return $this->hasMany(Notification::class, 'id_account', 'id');
    }

    public function roles () : BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }

    public function dataVersionStudent () : HasOneThrough
    {
        return $this->hasOneThrough(
            DataVersionStudent::class,
            Student::class,
            'id_account',
            'id_student',
            'id',
            'id',
        );
    }

    public function dataVersionTeacher () : HasOneThrough
    {
        return $this->hasOneThrough(
            DataVersionTeacher::class,
            Teacher::class,
            'id_account',
            'id_teacher',
            'id',
            'id',
        );
    }
}
