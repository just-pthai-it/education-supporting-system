<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacultyClassController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ModuleClassController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudySessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamScheduleController;

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
    Route::group(['prefix' => 'notification'], function ()
    {
        Route::get('{id_sender}/{offset?}',
                   [NotificationController::class, 'getSentNotifications']);

        Route::group(['prefix' => 'push'], function ()
        {
            Route::post('faculty-class', [NotificationController::class, 'pushFCNotification']);

            Route::post('module-class', [NotificationController::class, 'pushMCNotification']);
        });

        Route::post('delete', [NotificationController::class, 'deleteNotifications']);
    });

    Route::group(['prefix' => 'teachers'], function ()
    {
        Route::get('', [TeacherController::class, 'getTeachersByIdDepartment']);

        Route::group(['prefix' => 'notification'], function ()
        {
            Route::get('{id_account}/{offset}',
                       [NotificationController::class, 'getReceivedNotifications']);
        });

        Route::group(['prefix' => '{id_teacher}'], function ()
        {
            Route::get('schedules', [ScheduleController::class, 'getTeacherSchedules']);

            Route::get('exam-schedules',
                       [ExamScheduleController::class, 'getTeacherExamSchedules']);
        });
    });

    Route::group(['prefix' => 'import-data'], function ()
    {
        Route::post('roll-call', [ResourceController::class, 'uploadRollCallFile']);

        Route::post('schedule', [ResourceController::class, 'uploadScheduleFile']);

        Route::post('exam-schedule', [ResourceController::class, 'uploadExamScheduleFile']);

        Route::post('curriculum', [ResourceController::class, 'uploadCurriculumFile']);

    });

    Route::get('faculty', [FacultyController::class, 'getIDFaculties']);

    Route::get('academic-year', [AcademicYearController::class, 'getRecentAcademicYears']);

    Route::get('academic-year2', [AcademicYearController::class, 'getAcademicYearsWithTrainingType']);

    Route::get('faculty-class', [FacultyClassController::class, 'getFacultyClasses']);

    Route::get('module-class/{id_teacher}',
               [ModuleClassController::class, 'getRecentModuleClasses']);

    Route::group(['prefix' => 'module-classes'], function ()
    {
        Route::put('update', [ModuleClassController::class, 'updateModuleClass']);
    });

    Route::get('study-session', [StudySessionController::class, 'getRecentStudySessions']);

    Route::post('/account/change-password', [AccountController::class, 'changePassword']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'departments'], function ()
    {
        Route::group(['prefix' => '{id_department}'], function ()
        {
            Route::get('schedules', [ScheduleController::class, 'getDepartmentSchedules']);

            Route::get('exam-schedules',
                       [ExamScheduleController::class, 'getDepartmentExamSchedules']);
        });
    });

    Route::group(['prefix' => 'faculty'], function ()
    {

    });

    Route::group(['prefix' => 'other-department'], function ()
    {

    });

    Route::group(['prefix' => 'feedbacks'], function ()
    {
        Route::post('send-feedback', [FeedbackController::class, 'createFeedback']);

        Route::get('', [FeedbackController::class, 'getAllFeedbacks']);
    });

    Route::group(['prefix' => 'exam-schedules'], function ()
    {
        Route::put('update', [ExamScheduleController::class, 'updateExamSchedule']);
    });

    Route::get('users', [UserController::class, 'getUserInfo']);
});
