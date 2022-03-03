<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Account extends Model
{
    use HasFactory;

    public const table = 'account';
    public const table_as = 'account as acc';

    protected $table = 'account';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'username',
        'password',
        'qldt_password',
        'email',
        'phone_number',
        'id_role',
        'id_user',
        'uuid',
    ];

    protected $hidden = [
        'uuid',
    ];

    /**
     * @throws Exception
     */
    public function accountable () : BelongsTo
    {
        switch ($this->original['id_role'])
            {
            case 1:
                return $this->belongsTo(Student::class, 'id_user', 'id');
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
                return $this->belongsTo(Teacher::class, 'id_user', 'id');
            case 9:
            case 10:
                return $this->belongsTo(OtherDepartment::class, 'id_user', 'id');
            default:
                throw new Exception();
        }
    }

    public function devices () : HasMany
    {
        return $this->hasMany(Device::class, 'id_account', 'id');
    }

    public function notifications () : BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'notification_account',
                                    'id_account', 'id_notification');
    }

    public function sendNotification () : HasMany
    {
        return $this->hasMany(Notification::class, 'id_sender', 'id');
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
