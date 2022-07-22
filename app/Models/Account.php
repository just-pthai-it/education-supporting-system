<?php

namespace App\Models;

use Exception;
use App\Models\Traits\Filterable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Account extends Authenticatable implements JWTSubject
{
    use HasFactory, Filterable, Notifiable;

    public const TABLE = 'accounts';
    public const TABLE_AS = 'accounts as accs';

    protected $table = 'accounts';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'username',
        'password',
        'qldt_password',
        'email',
        'phone',
        'id_role',
        'accountable_type',
        'accountable_id',
        'created_at',
        'updated_at',
        'uuid',
    ];

    protected $hidden = [
        'uuid',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @throws Exception
     */
    public function accountable () : MorphTo
    {
        return $this->morphTo();
    }

    public function fcmRegistrationTokens () : HasMany
    {
        return $this->hasMany(FcmRegistrationToken::class, 'id_account', 'id');
    }

    public function notificationsReceived () : BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'account_notification', 'id_account',
                                    'id_notification')->withPivot(['read_at']);
    }

    public function notificationsSent () : HasOne
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

    public function getJWTIdentifier ()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims () : array
    {
        return [];
    }
}
