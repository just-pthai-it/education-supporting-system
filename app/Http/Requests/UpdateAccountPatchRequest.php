<?php

namespace App\Http\Requests;

use App\Http\Requests\Abstracts\ACustomFormRequest;

class UpdateAccountPatchRequest extends ACustomFormRequest
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
            'phone'                    => 'required|exclude_if:phone,',
            'settings'                 => ['required', 'sometimes', 'array'],
            "settings.google_calendar" => ["required", "sometimes", "boolean"]
        ];
    }
}
