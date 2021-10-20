<?php

namespace App\Providers;

use App\Repositories\AcademicYearRepository;
use App\Repositories\AccountRepository;
use App\Repositories\ClassRepository;
use App\Repositories\Contracts\AcademicYearRepositoryContract;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\ClassRepositoryContract;
use App\Repositories\Contracts\DataVersionStudentRepositoryContract;
use App\Repositories\Contracts\DataVersionTeacherRepositoryContract;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\DeviceRepositoryContract;
use App\Repositories\Contracts\FacultyRepositoryContract;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Repositories\Contracts\ModuleRepositoryContract;
use App\Repositories\Contracts\NotificationRepositoryContract;
use App\Repositories\Contracts\OtherDepartmentRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\SchoolYearRepositoryContract;
use App\Repositories\Contracts\StudentRepositoryContract;
use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\DataVersionStudentRepository;
use App\Repositories\DataVersionTeacherRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\FacultyRepository;
use App\Repositories\ModuleClassRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OtherDepartmentRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\StudentRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        DataVersionStudentRepositoryContract::class  => DataVersionStudentRepository::class,
        DataVersionTeacherRepositoryContract::class  => DataVersionTeacherRepository::class,
        OtherDepartmentRepositoryContract::class     => OtherDepartmentRepository::class,
        NotificationRepositoryContract::class        => NotificationRepository::class,
        AcademicYearRepositoryContract::class        => AcademicYearRepository::class,
        ModuleClassRepositoryContract::class         => ModuleClassRepository::class,
        DepartmentRepositoryContract::class          => DepartmentRepository::class,
        SchoolYearRepositoryContract::class          => SchoolYearRepository::class,
        ScheduleRepositoryContract::class            => ScheduleRepository::class,
        TeacherRepositoryContract::class             => TeacherRepository::class,
        StudentRepositoryContract::class             => StudentRepository::class,
        AccountRepositoryContract::class             => AccountRepository::class,
        FacultyRepositoryContract::class             => FacultyRepository::class,
        DeviceRepositoryContract::class              => DeviceRepository::class,
        ModuleRepositoryContract::class              => ModuleRepository::class,
        ClassRepositoryContract::class               => ClassRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register ()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot ()
    {
    }
}
