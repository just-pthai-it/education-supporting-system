<?php

namespace App\Providers;

use App\Services\AcademicYearService;
use App\Services\AccountService;
use App\Services\AuthService;
use App\Services\Contracts\AcademicYearServiceContract;
use App\Services\Contracts\AccountServiceContract;
use App\Services\Contracts\AuthServiceContract;
use App\Services\Contracts\ClassServiceContract;
use App\Services\Contracts\FacultyServiceContract;
use App\Services\Contracts\FileUploadServiceContract;
use App\Services\Contracts\ModuleClassServiceContract;
use App\Services\Contracts\NotificationServiceContract;
use App\Services\Contracts\NotifyServiceContract;
use App\Services\Contracts\ScheduleServiceContract;
use App\Services\ClassService;
use App\Services\FacultyService;
use App\Services\FileUploadService;
use App\Services\ModuleClassService;
use App\Services\NotificationService;
use App\Services\NotifyService;
use App\Services\ScheduleService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        NotificationServiceContract::class => NotificationService::class,
        AcademicYearServiceContract::class => AcademicYearService::class,
        ModuleClassServiceContract::class  => ModuleClassService::class,
        FileUploadServiceContract::class   => FileUploadService::class,
        ScheduleServiceContract::class     => ScheduleService::class,
        AccountServiceContract::class      => AccountService::class,
        FacultyServiceContract::class      => FacultyService::class,
        NotifyServiceContract::class       => NotifyService::class,
        ClassServiceContract::class        => ClassService::class,
        AuthServiceContract::class         => AuthService::class,
    ];

    /**
     * Register any application services.
     * @return void
     */
    public function register ()
    {
    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot ()
    {
    }
}
