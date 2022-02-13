<?php

use App\Models\Faculty;
use App\BusinessClasses\aa;
use App\Models\AcademicYear;
use App\Models\Account;
use App\Models\DataVersionStudent;
use App\Models\ModuleClass;
use App\Models\Schedule;
use App\Models\StudySession;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\Student;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\AccountCollection;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test', function ()
{

});

Route::get('view', function ()
{
    return view('welcome');
});
