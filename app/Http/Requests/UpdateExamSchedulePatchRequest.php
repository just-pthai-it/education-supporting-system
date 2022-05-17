<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\ExamSchedule;
use App\Http\Requests\Abstracts\ACustomFormRequest;

class UpdateExamSchedulePatchRequest extends ACustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize () : bool
    {
        $idExamSchedule = $this->route('id_exam_schedule');
        $permissions    = Role::find($this->user()->id_role)->permissions()
                              ->pluck('permissions.id')->toArray();

        if (!in_array(33, $permissions))
        {
            return false;
        }

        $validIdDepartment = ExamSchedule::find($idExamSchedule)
            ->moduleClass->module()->first(['modules.id_department'])->id_department;
        $userIdDepartment  = $this->user()->accountable->id_department ?? '';

        return $validIdDepartment == $userIdDepartment;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules () : array
    {
        return [
            'id_room' => 'required',
        ];
    }
}
