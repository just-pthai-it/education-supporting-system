<?php

namespace App\Providers;

use App\Repositories\TagRepository;
use App\Repositories\RoomRepository;
use App\Repositories\RoleRepository;
use App\Repositories\AdminRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\CurriculumRepository;
use App\Repositories\AcademicYearRepository;
use App\Repositories\AccountRepository;
use App\Repositories\ClassRepository;
use App\Repositories\ExamScheduleRepository;
use App\Repositories\TrainingTypeRepository;
use App\Repositories\FixedScheduleRepository;
use App\Repositories\PasswordResetRepository;
use App\Repositories\ThirdPartyTokenRepository;
use App\Repositories\DataVersionTeacherRepository;
use App\Repositories\DataVersionStudentRepository;
use App\Repositories\FcmRegistrationTokenRepository;
use App\Repositories\Contracts\TagRepositoryContract;
use App\Repositories\Contracts\RoomRepositoryContract;
use App\Repositories\Contracts\RoleRepositoryContract;
use App\Repositories\Contracts\AdminRepositoryContract;
use App\Repositories\Contracts\FeedbackRepositoryContract;
use App\Repositories\Contracts\PermissionRepositoryContract;
use App\Repositories\Contracts\CurriculumRepositoryContract;
use App\Repositories\Contracts\AcademicYearRepositoryContract;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\ClassRepositoryContract;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\FacultyRepositoryContract;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Repositories\Contracts\ModuleRepositoryContract;
use App\Repositories\Contracts\NotificationRepositoryContract;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use App\Repositories\Contracts\TrainingTypeRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;
use App\Repositories\Contracts\PasswordResetRepositoryContract;
use App\Repositories\Contracts\OtherDepartmentRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\TermRepositoryContract;
use App\Repositories\Contracts\StudentRepositoryContract;
use App\Repositories\Contracts\StudySessionRepositoryContract;
use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\DepartmentRepository;
use App\Repositories\FacultyRepository;
use App\Repositories\ModuleClassRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OtherDepartmentRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\TermRepository;
use App\Repositories\StudentRepository;
use App\Repositories\StudySessionRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ThirdPartyTokenRepositoryContract;
use App\Repositories\Contracts\DataversionteacherRepositoryContract;
use App\Repositories\Contracts\DataVersionStudentRepositoryContract;
use App\Repositories\Contracts\FcmRegistrationtokenRepositoryContract;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    public array $bindings = [
        OtherDepartmentRepositoryContract::class      => OtherDepartmentRepository::class,
        FixedScheduleRepositoryContract::class        => FixedScheduleRepository::class,
        NotificationRepositoryContract::class         => NotificationRepository::class,
        AcademicYearRepositoryContract::class         => AcademicYearRepository::class,
        ExamScheduleRepositoryContract::class         => ExamScheduleRepository::class,
        StudySessionRepositoryContract::class         => StudySessionRepository::class,
        ModuleClassRepositoryContract::class          => ModuleClassRepository::class,
        CurriculumRepositoryContract::class           => CurriculumRepository::class,
        DepartmentRepositoryContract::class           => DepartmentRepository::class,
        PermissionRepositoryContract::class           => PermissionRepository::class,
        ScheduleRepositoryContract::class             => ScheduleRepository::class,
        FeedbackRepositoryContract::class             => FeedbackRepository::class,
        TeacherRepositoryContract::class              => TeacherRepository::class,
        StudentRepositoryContract::class              => StudentRepository::class,
        AccountRepositoryContract::class              => AccountRepository::class,
        FacultyRepositoryContract::class              => FacultyRepository::class,
        ModuleRepositoryContract::class               => ModuleRepository::class,
        ClassRepositoryContract::class                => ClassRepository::class,
        TermRepositoryContract::class                 => TermRepository::class,
        RoomRepositoryContract::class                 => RoomRepository::class,
        RoleRepositoryContract::class                 => RoleRepository::class,
        TagRepositoryContract::class                  => TagRepository::class,
        TrainingTypeRepositoryContract::class         => TrainingTypeRepository::class,
        DataversionTeacherRepositoryContract::class   => DataVersionTeacherRepository::class,
        FcmRegistrationtokenRepositoryContract::class => FcmRegistrationTokenRepository::class,
        DataVersionStudentRepositoryContract::class   => DataVersionStudentRepository::class,
        PasswordResetRepositoryContract::class        => PasswordResetRepository::class,
        AdminRepositoryContract::class                => AdminRepository::class,
        ThirdPartyTokenRepositoryContract::class      => ThirdPartyTokenRepository::class,

    ];

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot ()
    {
        //        Faculty::resolveRelationUsing('value', function ($facultyModel)
        //        {
        //            return $facultyModel->hasMany(Department::class, 'id_faculty', 'id');
        //        });
    }
}
