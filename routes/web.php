<?php

use App\Models\AcademicYear;
use App\Models\Account;
use App\Models\DataVersionStudent;
use App\Models\ModuleClass;
use App\Models\Schedule;
use App\Models\StudySession;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\Student;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

//    return Teacher::with(['moduleClasses:id,id_teacher',
//                          'moduleClasses.schedules:id,id_module_class,date,shift,id_room'])
//                  ->where('id', '0884')
//                  ->get(['id']);
//    return StudySession::orderBy('id', 'desc')->limit(7)->pluck('id')->toArray();
//    return Teacher::find('0884')->examSchedules()
//                  ->join(ModuleClass::table_as, 'mc.id', '=', 'exam_schedule.id_module_class')
//                  ->get(['id_module_class', 'mc.name', 'method', 'date_start', 'time_start', 'id_room'])
//                  ->toArray();
    echo Str::orderedUuid();
});

Route::get('view', function ()
{
    return view('welcome');
});
