<?php

namespace App\Providers;

use App\Events\FixedScheduleCreatedOrUpdated;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendFixedScheduleCreatedOrUpdateNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        FixedScheduleCreatedOrUpdated::class => [
            SendFixedScheduleCreatedOrUpdateNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     * @return void
     */
    public function boot ()
    {
        //
    }
}
