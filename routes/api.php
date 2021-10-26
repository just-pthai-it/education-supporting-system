<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacultyClassController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ModuleClassController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\StudySessionController;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth'], function ()
{
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['prefix' => 'notification'], function ()
{
    Route::get('{id_sender}/{offset?}', [NotificationController::class, 'getSentNotifications']);

    Route::group(['prefix' => 'push'], function ()
    {
        Route::post('faculty-class', [NotificationController::class, 'pushFCNotification']);

        Route::post('module-class', [NotificationController::class, 'pushMCNotification']);
    });

    Route::post('delete', [NotificationController::class, 'deleteNotifications']);

});

Route::group(['prefix' => 'teacher'], function ()
{
    Route::group(['prefix' => 'notification'], function ()
    {
        Route::get('{id_account}/{offset}',
                   [NotificationController::class, 'getReceivedNotifications']);
    });
});

Route::group(['prefix' => 'import-data'], function ()
{
    Route::post('roll-call', [ResourceController::class, 'uploadRollCallFile']);

    Route::post('schedule', [ResourceController::class, 'uploadScheduleFile']);
});


Route::get('faculty', [FacultyController::class, 'getIDFaculties']);

Route::get('academic-year', [AcademicYearController::class, 'getRecentAcademicYears']);

Route::get('faculty-class', [FacultyClassController::class, 'getFacultyClasses']);

Route::get('module-class/{id_teacher}', [ModuleClassController::class, 'getRecentModuleClasses']);

Route::get('study-session', [StudySessionController::class, 'getRecentStudySessions']);

Route::post('/account/change-password', [AccountController::class, 'changePassword']);

Route::post('/auth/logout', [AuthController::class, 'logout']);
