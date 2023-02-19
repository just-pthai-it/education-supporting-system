<?php

namespace App\Http\Requests\Curriculum;

use App\Http\Requests\Abstracts\ACustomFormRequest;

class CreateCurriculumPostRequest extends ACustomFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'                    => ['required', 'string'],
            'modules'                 => ['sometimes', 'required', 'array',],
            'modules.*.id'            => ['sometimes', 'required', 'string'],
            'modules.*.name'          => ['sometimes', 'required', 'string'],
            'modules.*.credit'        => ['sometimes', 'required', 'integer'],
            'modules.*.semester'      => ['sometimes', 'required', 'integer'],
            'modules.*.theory'        => ['sometimes', 'present', 'integer', 'nullable'],
            'modules.*.exercise'      => ['sometimes', 'present', 'integer', 'nullable'],
            'modules.*.project'       => ['sometimes', 'present', 'integer', 'nullable'],
            'modules.*.experiment'    => ['sometimes', 'present', 'integer', 'nullable'],
            'modules.*.practice'      => ['sometimes', 'present', 'integer', 'nullable'],
            'modules.*.option'        => ['sometimes', 'present', 'integer', 'nullable'],
            'modules.*.id_department' => ['sometimes', 'required', 'string'],
        ];
    }
}
