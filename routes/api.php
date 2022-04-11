<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ModuleClassController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudySessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TrainingTypeController;
use App\Http\Controllers\FixedScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware(['default_header'])->group(function ()
{
    Route::group(['prefix' => 'auth'], function ()
    {
        Route::post('login', [AuthController::class, 'login']);
    });
});

Route::middleware(['cus.auth', 'default_header'])->group(function ()
{
    Route::group(['prefix' => 'schedules'], function ()
    {
        Route::patch('update', [ScheduleController::class, 'update']);
    });


    Route::group(['prefix' => 'teachers'], function ()
    {
        Route::group(['prefix' => '{id_teacher}'], function ()
        {
            Route::get('', [TeacherController::class, 'get']);

            Route::get('module-classes',
                       [TeacherController::class, 'getModuleClassesByStudySessions']);

            Route::get('schedules', [TeacherController::class, 'getSchedules']);

            Route::get('exam-schedules', [TeacherController::class, 'getExamSchedules']);

            Route::get('fixed-schedules', [TeacherController::class, 'getFixedSchedules']);
        });
    });

    Route::group(['prefix' => 'import-data'], function ()
    {
        Route::post('roll-call', [ResourceController::class, 'uploadRollCallFile']);

        Route::post('schedule', [ResourceController::class, 'uploadScheduleFile']);

        Route::post('exam-schedule', [ResourceController::class, 'uploadExamScheduleFile']);

        Route::post('curriculum', [ResourceController::class, 'uploadCurriculumFile']);

    });


    Route::get('academic-year', [AcademicYearController::class, 'getRecentAcademicYears']);

    Route::get('academic-year2',
               [AcademicYearController::class, 'getAcademicYearsWithTrainingType']);

    Route::group(['prefix' => 'training-types'], function ()
    {
        Route::get('', [TrainingTypeController::class, 'readMany']);
    });

    Route::get('faculty-class',
               [ClassController::class, 'getClassesByIdAcademicYearsAndIdFaculties']);

    Route::get('module-class/{id_teacher}',
               [ModuleClassController::class, 'getModuleClassesByIdTeacher']);

    Route::group(['prefix' => 'module-classes'], function ()
    {
        Route::put('update', [ModuleClassController::class, 'updateModuleClass']);
    });

    Route::get('study-sessions', [StudySessionController::class, 'readMany']);

    Route::post('/account/change-password', [AccountController::class, 'changePassword']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'accounts'], function ()
    {
        Route::group(['prefix' => '{uuid_account}'], function ()
        {
            Route::get('notifications', [AccountController::class, 'readManyNotifications']);
        });

        Route::group(['prefix' => 'update'], function ()
        {
            Route::patch('{id_account}', [AccountController::class, 'update']);
        });
    });

    Route::group(['prefix' => 'departments'], function ()
    {
        Route::group(['prefix' => '{id_department}'], function ()
        {
            Route::get('module-classes',
                       [DepartmentController::class, 'getModuleClasses']);

            Route::get('schedules', [DepartmentController::class, 'getSchedules']);

            Route::get('exam-schedules', [DepartmentController::class, 'getExamSchedules']);

            Route::get('fixed-schedules',
                       [DepartmentController::class, 'getFixedSchedules']);

            Route::get('teachers', [DepartmentController::class, 'getTeachers']);

            Route::delete('module-classes/delete',
                          [DepartmentController::class, 'destroyModuleClassesByStudySession']);
        });
    });


    Route::group(['prefix' => 'fixed-schedules'], function ()
    {
        Route::get('', [FixedScheduleController::class, 'read']);

        Route::post('create', [FixedScheduleController::class, 'create']);

        Route::put('update', [FixedScheduleController::class, 'update']);
    });


    Route::group(['prefix' => 'rooms'], function ()
    {
        Route::get('', [RoomController::class, 'read']);
    });


    Route::group(['prefix' => 'faculties'], function ()
    {
        Route::get('', [FacultyController::class, 'readMany']);
    });


    Route::group(['prefix' => 'other-department'], function ()
    {

    });


    Route::group(['prefix' => 'feedback'], function ()
    {
        Route::post('create', [FeedbackController::class, 'create']);

        Route::get('', [FeedbackController::class, 'readMany']);
    });


    Route::group(['prefix' => 'exam-schedules'], function ()
    {
        Route::put('update', [ExamScheduleController::class, 'update']);
    });

    Route::group(['prefix' => 'notifications'], function ()
    {
        Route::post('create', [NotificationController::class, 'store']);
    });


    Route::get('me', [UserController::class, 'getUserInfo']);
});

Route::get('bad-request', function ()
{
    return response('', 400);
});
