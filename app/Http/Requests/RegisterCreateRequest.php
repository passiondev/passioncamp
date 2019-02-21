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
            'contact.first_name' => 'required',
            'contact.last_name' => 'required',
            'contact.email' => 'required|email',
            'contact.phone' => 'required',
            'billing.street' => 'required',
            'billing.city' => 'required',
            'billing.state' => 'required',
            'billing.zip' => 'required',
            'num_tickets' => 'required|numeric|min:1',
            'tickets.*.first_name' => 'required',
            'tickets.*.last_name' => 'required',
            'tickets.*.gender' => 'required',
            'tickets.*.grade' => 'required',
            'payment_type' => 'required',
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
