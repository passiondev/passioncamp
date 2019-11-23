<?php

namespace App\Billing;

use Illuminate\Foundation\Testing\WithFaker;

class FakePaymentGateway implements PaymentGateway
{
    use WithFaker;

    protected $failing = false;

    public function __construct()
    {
        $this->setUpFaker();
    }

    public function charge($amount, $token)
    {
        if ($this->failing) {
            throw new PaymentFailedException();
        }

        return new Charge([
            'source' => 'fake',
            'identifier' => 'ch_'.$this->faker->md5,
            'amount' => $amount,
            'card_last_four' => substr($this->faker->creditCardNumber, -4),
            'card_brand' => $this->faker->creditCardType,
        ]);
    }

    public function getValidTestToken()
    {
        return 'tok_'.$this->faker->md5;
    }

    public function getFailingTestToken()
    {
        $this->failing = true;

        return 'invalid-token';
    }
}
