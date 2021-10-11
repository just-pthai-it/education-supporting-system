<?php

namespace App\Http\FormRequest;

class PushNotificationForm extends BaseForm
{
    protected function getRules () : array
    {
        return [
            'class_list' => 'required',
            'info'       => 'required'
        ];
    }
}
