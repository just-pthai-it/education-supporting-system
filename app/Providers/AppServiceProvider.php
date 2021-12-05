<?php

namespace App\Providers;

use App\Services\FeedbackService;
use Illuminate\Support\Facades\URL;
use App\Services\AcademicYearService;
use App\Services\AccountService;
use App\Services\AuthService;
use App\Services\ExamScheduleService;
use App\Services\RollCallExcelService;
use App\Services\ExamScheduleExcelService;
use App\Services\Contracts\FeedbackServiceContract;
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
use App\Services\Contracts\StudySessionServiceContract;
use App\Services\FacultyService;
use App\Services\FileUploadService;
use App\Services\ModuleClassService;
use App\Services\NotificationService;
use App\Services\NotifyService;
use App\Services\ScheduleService;
use App\Services\StudySessionService;
use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\ExamScheduleServiceContract;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ExamScheduleServiceContract::class => ExamScheduleService::class,
        NotificationServiceContract::class => NotificationService::class,
        AcademicYearServiceContract::class => AcademicYearService::class,
        StudySessionServiceContract::class => StudySessionService::class,
        ModuleClassServiceContract::class  => ModuleClassService::class,
        FileUploadServiceContract::class   => FileUploadService::class,
        FeedbackServiceContract::class     => FeedbackService::class,
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
        $this->app->bind('excel_roll_call', function ()
        {
            return new RollCallExcelService();
        });

        $this->app->bind('excel_exam_schedule', function ()
        {
            return new ExamScheduleExcelService();
        });
    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot ()
    {
        URL::forceScheme('https');
    }
}
