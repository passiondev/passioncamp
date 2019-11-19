<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class AccountTicketCreateRequest extends FormRequest
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
        return [
            'ticket.agegroup' => 'required',
            'ticket.first_name' => 'required',
            'ticket.last_name' => 'required',
            'ticket.gender' => 'required',
            'ticket.grade' => 'required_if:ticket.agegroup,student',
            'contact.name' => 'required',
            'contact.email' => 'required|email',
            'contact.phone' => 'required',
        ];
    }

    public function persist()
    {
        $user = 'pcc' == $this->user()->organization->slug
            ? User::firstOrNew(['email' => $this->input('contact.email')])
            : new User();

        $user->fill([
            'person' => [
                'name' => $this->input('contact.name'),
                'email' => $this->input('contact.email'),
                'phone' => $this->input('contact.phone'),
            ],
        ])->save();

        $order = $user->orders()->create([
            'organization_id' => $this->user()->organization_id,
        ]);

        $order->tickets()->create([
            'agegroup' => $this->input('ticket.agegroup'),
            'person' => [
                'considerations' => $this->input('considerations'),
                'first_name' => $this->input('ticket.first_name'),
                'last_name' => $this->input('ticket.last_name'),
                'gender' => $this->input('ticket.gender'),
                'grade' => $this->input('ticket.grade'),
                'allergies' => $this->input('ticket.allergies'),
            ],
        ]);
    }
}
