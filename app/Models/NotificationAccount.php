<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationAccount extends Model
{
    use HasFactory;

    public const table = 'notification_account';
    public const table_as = 'notification_account as na';

    protected $table = 'notification_account';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_notification',
        'id_account'
    ];
}
