<?php

namespace App\Http\Requests\Module;

use App\Http\Requests\Abstracts\ACustomFormRequest;

class CreateModulePostRequest extends ACustomFormRequest
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
        return [
            'id'            => ['required', 'string'],
            'name'          => ['required', 'string'],
            'credit'        => ['required', 'integer'],
            'semester'      => ['required', 'integer'],
            'theory'        => ['present', 'integer', 'nullable'],
            'exercise'      => ['present', 'integer', 'nullable'],
            'project'       => ['present', 'integer', 'nullable'],
            'experiment'    => ['present', 'integer', 'nullable'],
            'practice'      => ['present', 'integer', 'nullable'],
            'option'        => ['present', 'integer', 'nullable'],
            'id_department' => ['required', 'string'],
            'id_curriculum' => ['sometimes', 'required', 'integer'],
        ];
    }
}
