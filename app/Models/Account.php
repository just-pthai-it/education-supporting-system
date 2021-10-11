<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'email',
        'password',
        'qldt_password',
        'permission'
    ];

    public function student () : HasOne
    {
        return $this->hasOne(Student::class, 'id_account', 'id');
    }

    public function teacher () : hasOne
    {
        return $this->hasOne(Teacher::class, 'id_account', 'id');
    }

    public function otherDepartment () : hasOne
    {
        return $this->hasOne(OtherDepartment::class, 'id_account', 'id');
    }

    public function department () : hasOne
    {
        return $this->hasOne(Department::class, 'id_account', 'id');
    }

    public function faculty () : hasOne
    {
        return $this->hasOne(Faculty::class, 'id_account', 'id');
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
