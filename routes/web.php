<?php

use App\Models\AcademicYear;
use App\Models\ModuleClass;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

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

    return Cache::get('MHT_special_module_classes');
});
