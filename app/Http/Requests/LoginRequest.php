<?php

namespace App\Http\Requests;

use App\Http\Requests\Abstracts\ACustomFormRequest;

class LoginRequest extends ACustomFormRequest
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
            'username'    => 'required',
            'password'    => 'required|min:4',
            'remember_me' => ['required', 'boolean']
        ];
    }
}
