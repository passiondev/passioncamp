<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'capacity' => 'required|numeric|min:1|max:6',
            'description' => 'max:255',
            'notes' => 'max:255',
        ];

        $rules_for_admin = [
            'name' => 'required',
            'hotel_id' => 'required',
        ];

        if ($this->user()->isSuperAdmin()) {
            $rules = array_merge($rules_for_admin, $rules);
        }

        return $rules;
    }

    public function validator()
    {
        $validator = Validator::make($this->all(), $this->rules());

        return $this->after($validator);
    }

    public function after($validator)
    {
        $this->removeParameters();

        return $validator;
    }

    public function removeParameters()
    {
        if (!$this->user()->isSuperAdmin()) {
            $this->offsetUnset('name');
            $this->offsetUnset('hotel_id');
            $this->offsetUnset('confirmation_number');
        }
    }
}
