<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Ticket;
use App\User;

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

    public function forOrganization($organization)
    {
        $this->_data['organization_id'] = $organization->id;

        return $this;
    }

    public function withTicketPrice($ticketPrice)
    {
        $this->_data['ticketPrice'] = $ticketPrice;

        return $this;
    }

    private function ticketPrice()
    {
        return $this->_data['ticketPrice'];
    }

    private function organization()
    {
        return $this->_data['organization_id'];
    }

    public function persist()
    {
        $user = $this->createUser();

        $order = $user->orders()->create([
            'organization_id' => $this->organization(),
        ]);

        if ($this->getFundAmount() > 0) {
            $order->donations()->create([
                'type' => 'donation',
                'organization_id' => $this->organization(),
                'price' => 100 * $this->getFundAmount(),
            ]);
        }

        $order->tickets()->saveMany(
            $this->buildTickets()
        );

        return $order;
    }

    private function createUser()
    {
        $user = User::firstOrNew([
            'email' => $this->input('contact.email'),
        ]);

        $user->fill([
            'person' => array_collapse($this->only([
                'contact.first_name',
                'contact.last_name',
                'contact.email',
                'contact.phone',
                'billing.street',
                'billing.city',
                'billing.state',
                'billing.zip',
            ])),
        ])->save();

        return $user;
    }

    private function getFundAmount()
    {
        return $this->input('fund_amount') == 'other'
            ? $this->input('fund_amount_other')
            : $this->input('fund_amount');
    }

    public function buildTickets()
    {
        $tickets = collect($this->input('tickets'))
            ->map(function ($data) {
                return new Ticket([
                    'agegroup' => 'student',
                    'ticket_data' => collect($data)->only(['school', 'roommate_requested'])->merge(['code' => request('code')])->toArray(),
                    'price_in_dollars' => $this->ticketPrice(),
                    'organization_id' => $this->organization(),
                    'person' => array_only($data, [
                        'first_name', 'last_name', 'email', 'phone',
                        'gender', 'grade', 'allergies',
                        'considerations',
                    ]),
                ]);
            })
        ;

        for ($i = $tickets->count(); $i < $this->input('num_tickets'); $i++) {
            $tickets->push(
                new Ticket([
                    'agegroup' => 'student',
                    'price_in_dollars' => $this->ticketPrice(),
                    'organization_id' => $this->organization(),
                    'person' => [],
                ])
            );
        }

        return $tickets;
    }
}
