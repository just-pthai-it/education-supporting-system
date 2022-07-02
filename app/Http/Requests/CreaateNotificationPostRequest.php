<?php

namespace App\Http\Requests;

use App\Helpers\Constants;
use App\Http\Requests\Abstracts\ACustomFormRequest;

class CreaateNotificationPostRequest extends ACustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize () : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules () : array
    {
        $rules = [
            'data' => 'required|array',
            'type' => 'required|integer',
        ];

        switch ($this->route('option'))
        {
            case Constants::FOR_TEACHERS_IN_FACULTIES:
                $rules['taggable_ids.faculties'] = 'required|array';
                break;

            case Constants::FOR_TEACHERS_IN_DEPARTMENTS:
                $rules['taggable_ids.departments'] = 'required|array';
                break;

            case Constants::FOR_STUDENTS_IN_FACULTIES_AND_ACADEMIC_YEARS:
                $rules['taggable_ids.faculties']      = 'required|array';
                $rules['taggable_ids.academic_years'] = 'required|array';
                break;

            case Constants::FOR_STUDENTS_IN_MODULE_CLASSES:
                $rules['taggable_ids.module_classes'] = 'required|array';
                break;

            case Constants::FOR_STUDENTS_BY_IDS:
                $rules['id_students'] = 'required|array';
                break;
        }

        return $rules;
    }
}
