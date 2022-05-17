<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Http\Requests\Abstracts\ACustomFormRequest;

class UpdateExamScheduleTeacherPatchRequest extends ACustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize () : bool
    {
        $permissions = Role::find($this->user()->id_role)->permissions()
                           ->pluck('permissions.id')->toArray();

        return in_array(11, $permissions);
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules () : array
    {
        return [
            'note' => 'required',
        ];
    }
}
