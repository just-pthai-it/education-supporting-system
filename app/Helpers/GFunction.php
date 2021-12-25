<?php

namespace App\Helpers;

use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class GFunction
{
    public static function printError ($error)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date    = date('d/m/Y H:i:s');
        $message = $date . PHP_EOL;
        $message .= 'Code: ' . $error->getCode() . PHP_EOL;
        $message .= $error->getMessage() . PHP_EOL;
        $message .= $error->getFile() . '  ' . $error->getLine() . PHP_EOL;
        $message .= '=========================================================================================' .
                    PHP_EOL;

        file_put_contents(config('filesystems.disks.errors.file_path'), $message, FILE_APPEND);
    }

    public static function printFileImportException ($file_name, $message)
    {
        file_put_contents(storage_path('app/public/excels/errors/') . $file_name, $message);
    }

    public static function getDateTimeNow () : string
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $temp_date = date('d/m/Y H:i:s');
        $arr       = explode(' ', $temp_date);
        $arr2      = explode('/', $arr[0]);
        $date_time = $arr2[2] . '/' . $arr2[1] . '/' . $arr2[0] . ' ' . $arr[1];

        return $date_time;
    }

    public static function formatString ($str) : string
    {
        $str = preg_replace('/[ ]+/', ' ', $str);
        $str = str_replace('- ', '-', $str);
        $str = str_replace(' -', '-', $str);
        $str = trim($str, ' ');
        return $str;
    }


    public static function formatDate ($date) : string
    {
        $date_split = explode('/', $date);
        $date       = $date_split[2] . '-' . $date_split[1] . '-' . $date_split[0];
        return $date;
    }

    public static function excelValueToDate ($value) : string
    {
        return Date::excelToDateTimeObject($value)->format('Y-m-d');
    }

    public static function formatArray ($arr, $key) : array
    {
        $formatted_array = [];
        foreach ($arr as $a)
        {
            $formatted_array[] = [$key => $a];
        }
        return $formatted_array;
    }

    public static function convertToIDModuleClass ($id_module, $module_class_name) : string
    {

        $arr        = explode('-', $module_class_name);
        $arr_length = count($arr);
        return $id_module . '-' . $arr[$arr_length - 3] . '-' . $arr[$arr_length - 2] . '-' .
               $arr[$arr_length - 1];
    }

    public static function convertToDate ($temp_date) : string
    {
        $date = substr_replace($temp_date, '/20' . substr($temp_date, 12), 5);
        return self::formatDate($date);
    }

    public static function plusDate ($date, $plus)
    {
        return date('Y-m-d', strtotime($date . '+' . $plus . ' days'));
    }


    public static function uuidToBin ($uuid_value) : Expression
    {
        return DB::raw('UuidToBin(\'' . $uuid_value . '\')');
    }

    public static function uuidFromBin ($column_name) : Expression
    {
        return DB::raw('UuidFromBin(' . $column_name . ') as ' . $column_name);
    }
    /*
     *
     */


    /*
     *
     */
}
