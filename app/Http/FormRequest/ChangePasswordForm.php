<?php

namespace App\Http\FormRequest;

class ChangePasswordForm extends BaseForm
{
    protected function getRules () : array
    {
        return [
            'username'     => 'required',
            'password'     => 'required',
            'new_password' => 'required|min:4'
        ];
    }
}
