<?php

namespace App\Providers;

use App\Services\Contracts\CurriculumServiceContract;
use App\Services\Contracts\ModuleServiceContract;
use App\Services\Contracts\RabbitMQServiceContract;
use App\Services\CurriculumService;
use App\Services\ModuleService;
use App\Services\RabbitMQService;
use App\Services\RoomService;
use App\Services\TeacherService;
use App\Services\FeedbackService;
use Illuminate\Support\Facades\URL;
use App\Services\DepartmentService;
use App\Services\MailService;
use App\Services\AcademicYearService;
use App\Services\AccountService;
use App\Services\AuthService;
use App\Services\ExamScheduleService;
use App\Services\TrainingTypeService;
use App\Services\ExcelRollCallService;
use App\Services\ExcelScheduleService;
use App\Services\FixedScheduleService;
use App\Services\GoogleCalendarAPIsService;
use App\Services\ExcelCurriculumService;
use App\Services\ExcelExamScheduleService;
use App\Services\DataVersionStudentService;
use App\Services\FcmRegistrationTokenService;
use App\Services\Contracts\RoomServiceContract;
use App\Services\Contracts\TeacherServiceContract;
use App\Services\Contracts\FeedbackServiceContract;
use App\Services\Contracts\DepartmentServiceContract;
use App\Services\Contracts\MailServiceContract;
use App\Services\Contracts\AcademicYearServiceContract;
use App\Services\Contracts\AccountServiceContract;
use App\Services\Contracts\AuthServiceContract;
use App\Services\Contracts\ClassServiceContract;
use App\Services\Contracts\FacultyServiceContract;
use App\Services\Contracts\ResourceServiceContract;
use App\Services\Contracts\ModuleClassServiceContract;
use App\Services\Contracts\NotificationServiceContract;
use App\Services\Contracts\NotifyServiceContract;
use App\Services\Contracts\ScheduleServiceContract;
use App\Services\ClassService;
use App\Services\Contracts\StudySessionServiceContract;
use App\Services\FacultyService;
use App\Services\ResourceService;
use App\Services\ModuleClassService;
use App\Services\NotificationService;
use App\Services\NotifyService;
use App\Services\ScheduleService;
use App\Services\StudySessionService;
use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\ExamScheduleServiceContract;
use App\Services\Contracts\TrainingTypeServiceContract;
use App\Services\Contracts\FixedScheduleServiceContract;
use App\Services\Contracts\GoogleCalendarAPIsServiceContract;
use App\Services\Contracts\DataVersionStudentServiceContract;
use App\Services\Contracts\FcmRegistrationTokenServiceContract;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        GoogleCalendarAPIsServiceContract::class   => GoogleCalendarAPIsService::class,
        FixedScheduleServiceContract::class        => FixedScheduleService::class,
        ExamScheduleServiceContract::class         => ExamScheduleService::class,
        NotificationServiceContract::class         => NotificationService::class,
        AcademicYearServiceContract::class         => AcademicYearService::class,
        StudySessionServiceContract::class         => StudySessionService::class,
        ModuleClassServiceContract::class          => ModuleClassService::class,
        DepartmentServiceContract::class           => DepartmentService::class,
        ResourceServiceContract::class             => ResourceService::class,
        FeedbackServiceContract::class             => FeedbackService::class,
        ScheduleServiceContract::class             => ScheduleService::class,
        TeacherServiceContract::class              => TeacherService::class,
        AccountServiceContract::class              => AccountService::class,
        FacultyServiceContract::class              => FacultyService::class,
        NotifyServiceContract::class               => NotifyService::class,
        ClassServiceContract::class                => ClassService::class,
        AuthServiceContract::class                 => AuthService::class,
        RoomServiceContract::class                 => RoomService::class,
        MailServiceContract::class                 => MailService::class,
        TrainingTypeServiceContract::class         => TrainingTypeService::class,
        FcmRegistrationTokenServiceContract::class => FcmRegistrationTokenService::class,
        DataVersionStudentServiceContract::class   => DataVersionStudentService::class,
        ModuleServiceContract::class               => ModuleService::class,
        CurriculumServiceContract::class           => CurriculumService::class,
        RabbitMQServiceContract::class             => RabbitMQService::class,
    ];

    /**
     * Register any application services.
     * @return void
     */
    public function register ()
    {
        $this->app->bind('excel_roll_call', function ()
        {
            return new ExcelRollCallService();
        });

        $this->app->bind('excel_schedule', function ()
        {
            return new ExcelScheduleService();
        });

        $this->app->bind('excel_exam_schedule', function ()
        {
            return new ExcelExamScheduleService();
        });

        $this->app->bind('excel_curriculum', function ()
        {
            return new ExcelCurriculumService();
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
