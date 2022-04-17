<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class FixedSchedulePostRequest extends FormRequest
{
    protected $redirect = '/api/bad-request';

    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize () : bool
    {
        $permissions = Role::find($this->user()->id_role)->permissions()
                           ->pluck('permissions.id')->toArray();

        if ((!isset($this->all()['type']) || $this->all()['type'] == 'soft') &&
            in_array(12, $permissions))
        {
            return true;
        }

        if ($this->all()['type'] == 'hard' && in_array(14, $permissions))
        {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules () : array
    {
        return [
            'id_schedule' => 'required',
            'new_date'    => 'required_without:type|required_if:type,hard|exclude_unless:intend_time,null',
            'new_shift'   => 'required_without:type|required_if:type,hard|exclude_unless:intend_time,null',
            'new_id_room' => 'required_if:type,hard|exclude_unless:intend_time,null',
            'intend_time' => 'required_if:type,soft|required_without_all:new_date,new_shift,new_id_room|exclude_unless:new_date,null|exclude_unless:new_shift,null|exclude_unless:new_id_room,null|exclude_if:type,hard',
            'reason'      => 'required',
            'type'        => '',
        ];
    }
}
