<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FixedSchedulePatchRequest extends FormRequest
{
    protected $redirect = '/api/bad-request';

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
            'type'        => 'required',
            'new_date'    => '',
            'new_shift'   => '',
            'new_id_room' => '',
            'reason_deny' => '',
            'accepted_at' => '',
            'set_room_at' => '',
        ];
    }
}
