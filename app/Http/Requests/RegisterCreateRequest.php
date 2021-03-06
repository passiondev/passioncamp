<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterCreateRequest extends FormRequest
{
    private $_data = [];

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
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'num_tickets' => 'required|numeric|min:1',
            'tickets.*.first_name' => 'required',
            'tickets.*.last_name' => 'required',
            'tickets.*.gender' => 'required',
            'tickets.*.grade' => 'required',
            'payment_type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'tickets.*.first_name.required' => 'The first name field is required.',
            'tickets.*.last_name.required' => 'The last name field is required.',
            'tickets.*.gender.required' => 'The gender field is required.',
            'tickets.*.grade.required' => 'The grade field is required.',
        ];
    }

    public function fundAmount()
    {
        return $this->input('fund_amount') == 'other'
            ? $this->input('fund_amount_other')
            : $this->input('fund_amount');
    }

    public function orderData()
    {
        return [
            'rep' => $this->input('rep'),
        ];
    }

    public function ticketsData()
    {
        $tickets = collect($this->input('tickets'))->map(function ($data) {
            return [
                'agegroup' => 'student',
                'ticket_data' => array_only($data, ['school', 'roommate_requested']) + ['code' => $this->input('code')],
                'person' => array_only($data, [
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'gender',
                    'grade',
                    'allergies',
                    'considerations',
                ]),
            ];
        });

        while ($tickets->count() < $this->input('num_tickets')) {
            $tickets->push([
                'agegroup' => 'student',
                'person' => [],
            ]);
        }

        return $tickets->all();
    }

    public function wantsToPayDeposit()
    {
        return $this->input('payment_type') == 'deposit';
    }
}
