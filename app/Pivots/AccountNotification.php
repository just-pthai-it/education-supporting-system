<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountNotification extends Pivot
{

    protected $fillable = [
        'id',
        'id_account',
        'id_notification',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     * @var bool
     */
//    public $incrementing = true;
}