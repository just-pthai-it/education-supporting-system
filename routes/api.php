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
        Route::group(['prefix' => 'update'], function ()
        {
            Route::patch('{id_schedule}', [ScheduleController::class, 'update']);
        });
    });


    Route::group(['prefix' => 'teachers'], function ()
    {
        Route::group(['prefix' => '{id_teacher}'], function ()
        {
            Route::get('', [TeacherController::class, 'read']);

            Route::get('schedules', [ScheduleController::class, 'readManyByIdTeacher']);

            Route::get('exam-schedules', [ExamScheduleController::class, 'readManyByIdTeacher']);

            Route::get('fixed-schedules', [FixedScheduleController::class, 'readManyByIdTeacher']);
        });
    });

    Route::group(['prefix' => 'import-data'], function ()
    {
        Route::post('roll-call', [ResourceController::class, 'uploadRollCallFile']);

        Route::post('schedule', [ResourceController::class, 'uploadScheduleFile']);

        Route::post('exam-schedule', [ResourceController::class, 'uploadExamScheduleFile']);

        Route::post('curriculum', [ResourceController::class, 'uploadCurriculumFile']);

    });


    Route::group(['prefix' => 'academic-years'], function ()
    {
        Route::get('', [AcademicYearController::class, 'readMany']);
    });

    Route::group(['prefix' => 'training-types'], function ()
    {
        Route::get('', [TrainingTypeController::class, 'readMany']);
    });


    Route::group(['prefix' => 'classes'], function ()
    {
        Route::get('', [ClassController::class, 'readMany']);
    });


    Route::group(['prefix' => 'module-classes'], function ()
    {
        Route::get('', [ModuleClassController::class, 'readMany']);

        Route::put('update', [ModuleClassController::class, 'updateMany']);

        Route::delete('delete', [ModuleClassController::class, 'destroyMany']);
    });


    Route::group(['prefix' => 'study-sessions'], function ()
    {
        Route::get('', [StudySessionController::class, 'readMany']);
    });

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'accounts'], function ()
    {
        Route::patch('change-password/{uuid_account}',
                     [AccountController::class, 'changePassword']);

        Route::group(['prefix' => 'update'], function ()
        {
            Route::patch('{uuid_account}', [AccountController::class, 'update']);
        });
    });

    Route::group(['prefix' => 'departments'], function ()
    {
        Route::group(['prefix' => '{id_department}'], function ()
        {
            Route::get('module-classes',
                       [ModuleClassController::class, 'readManyByIdDepartment']);

            Route::get('schedules', [ScheduleController::class, 'readManyByIdDepartment']);

            Route::get('exam-schedules', [ExamScheduleController::class, 'readManyByIdDepartment']);

            Route::get('fixed-schedules',
                       [FixedScheduleController::class, 'readManyByIdDepartment']);

            Route::get('teachers', [DepartmentController::class, 'getTeachers']);
        });
    });


    Route::group(['prefix' => 'fixed-schedules'], function ()
    {
        Route::get('', [FixedScheduleController::class, 'readMany']);

        Route::post('create', [FixedScheduleController::class, 'create']);

        Route::put('update', [FixedScheduleController::class, 'update']);
    });


    Route::group(['prefix' => 'rooms'], function ()
    {
        Route::get('', [RoomController::class, 'readMany']);
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
