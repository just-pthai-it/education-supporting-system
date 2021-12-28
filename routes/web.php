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
    //    $old_module_classes    = Cache::get('MHT_special_module_classes') ?? [];
    //    $recent_id_school_year = array_pop($old_module_classes);
    //    $old_module_classes['Xác suất thống kê-2-20 (N06.BT1)'] = 'DSO04.2-2-20 (N06.BT1)';
    //    $old_module_classes['id_study_session'] = '43';
    //    Cache::forever('MHT_special_module_classes', $old_module_classes);

    //        Cache::forever('academic_years',
    //                       AcademicYear::orderBy('id', 'desc')->limit(18)->pluck('id', 'academic_year')
    //                                   ->toArray());
    //            $a = SchoolYear::orderBy('id', 'desc')->limit(14)
    //                           ->pluck('id', 'school_year')->toArray();
    //        array_shift($a);
    //        array_shift($a);

    //            Cache::forever('school_years',$a);
    //    return Cache::get('school_years');

    //    return SchoolYear::whereHas('examSchedules', function ($query)
    //    {
    //        return $query->where('id_student', '191201402');
    //    })->orderBy('id', 'desc')->limit(1)->select('id', 'school_year')->get()->toArray();
    //    $a = 'module_score';
    //    return DataVersionStudent::pluck($a)->find(['191201402', '191240003']);
    //     DataVersionStudent::select($a)->find(['191240003', '191201402'])->pluck($a);
    //    DataVersionStudent::find(['191240003', '191201402'])->pluck($a);
    //    return DataVersionStudent::where('id_student',  '191201402')
    //                             ->select('schedule')->get();
    //    return Account::find($id_account)->dataVersionStudent()->pluck($column_name)->first();
    //    return ModuleClass::whereIn('id', ['MHT02.3-1-1-21(N25.TH1)', 'MHT02.3-1-1-21(N27)'])
    //                      ->pluck('id')
    //                      ->toArray();

    //    return Student::select('id_account')->find(['191201402', '191240003'])->pluck('id_account')->toArray();

    //    return Student::find('191201402',['schedule_data_version'])->schedule_data_version;
    //    return Teacher::with('department', 'account', 'moduleClasses')->where('id', '0884')->get()
    //                  ->toArray();
    //        return ModuleClass::with(['teacher:id,name', 'schedules'])->whereNotNull('id_teacher')
    //                          ->get();
    //    return Teacher::with(['moduleClasses.schedules' => function ($query)
    //    {
    //        $query->where('date', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 YEAR)'))
    //              ->orderBy('id_module_class')
    //              ->orderBy('id');
    //    }])
    //                  ->where('id', '0884')
    //                  ->get(['id'])
    //    return Teacher::with(['moduleClasses:id,id_teacher',
    //                          'moduleClasses.schedules:id,id_module_class,date,shift,id_room'])
    //                  ->find('0884', ['id']);

    //    return Teacher::whereHas('moduleClasses', function ($query)
    //    {
    //        return $query->whereHas('schedules', function ($query)
    //        {
    ////            return $query->whereHas('schedules', ['0884']);
    //        });
    //    })
    //                  ->get();
    //                  ->find('0884', ['id']);

    //        return Teacher::with(['moduleClasses:id,id_teacher',
    //                              'moduleClasses.schedules:id,id_module_class,date,shift,id_room'])
    //                      ->where('id', '0884')
    //                      ->get(['id']);
    //    echo Str::orderedUuid();
    //    return Teacher::with([
    //
    //                             'examSchedules.moduleClass' => function ($query)
    //                             {
    //                                 return $query->whereIn('id_study_session', ['46'])
    //                                              ->select('id', 'name');
    //                             },'examSchedules.teachers:name',
    //                             ])
    //                  ->select('id', 'name')->find(Teacher::where('id_department', 'MHT')->pluck('id')->toArray())->toArray();
    //    var_dump(Account::all());
    //    return new UserResource(Account::find(1));
//    return new AccountCollection(Faculty::with(['departments'])->get(['id', 'name']));
    return response((FacultyResource::collection(Faculty::with(['departments:id,name,id_faculty'])->get(['id', 'name']))));

});

Route::get('view', function ()
{
    return view('welcome');
});
