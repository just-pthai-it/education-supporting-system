<?php

namespace App\Providers;

use App\Services\AcademicYearService;
use App\Services\AccountService;
use App\Services\Contracts\AcademicYearServiceContract;
use App\Services\Contracts\AccountServiceContract;
use App\Services\Contracts\DataServiceContract;
use App\Services\Contracts\DataVersionStudentServiceContract;
use App\Services\Contracts\DataVersionTeacherServiceContract;
use App\Services\Contracts\DeviceServiceContract;
use App\Services\Contracts\ExamScheduleServiceContract;
use App\Services\Contracts\FacultyClassServiceContract;
use App\Services\Contracts\FacultyServiceContract;
use App\Services\Contracts\LoginServiceContract;
use App\Services\Contracts\ModuleClassServiceContract;
use App\Services\Contracts\NotificationServiceContract;
use App\Services\Contracts\NotifyServiceContract;
use App\Services\Contracts\RegisterServiceContract;
use App\Services\Contracts\ScheduleServiceContract;
use App\Services\DataService;
use App\Services\DataVersionStudentService;
use App\Services\DataVersionTeacherService;
use App\Services\DeviceService;
use App\Services\ExamScheduleService;
use App\Services\FacultyClassService;
use App\Services\FacultyService;
use App\Services\LoginService;
use App\Services\ModuleClassService;
use App\Services\NotificationService;
use App\Services\NotifyService;
use App\Services\RegisterService;
use App\Services\ScheduleService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        DataVersionStudentServiceContract::class => DataVersionStudentService::class,
        DataVersionTeacherServiceContract::class => DataVersionTeacherService::class,
        FacultyClassServiceContract::class       => FacultyClassService::class,
        NotificationServiceContract::class       => NotificationService::class,
        AcademicYearServiceContract::class       => AcademicYearService::class,
        ExamScheduleServiceContract::class       => ExamScheduleService::class,
        ModuleClassServiceContract::class        => ModuleClassService::class,
        RegisterServiceContract::class           => RegisterService::class,
        ScheduleServiceContract::class           => ScheduleService::class,
        AccountServiceContract::class            => AccountService::class,
        FacultyServiceContract::class            => FacultyService::class,
        DeviceServiceContract::class             => DeviceService::class,
        NotifyServiceContract::class             => NotifyService::class,
        LoginServiceContract::class              => LoginService::class,
        DataServiceContract::class               => DataService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register ()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot ()
    {
    }
}
