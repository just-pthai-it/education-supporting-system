<?php

namespace App\Http\FormRequest;

class LoginForm extends BaseForm
{
    protected function getRules () : array
    {
        return [
            'username' => 'required',
            'password' => 'required|min:3'
        ];
    }
}
