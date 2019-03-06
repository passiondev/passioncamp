<?php

namespace App\Billing;

use Stripe\Error\InvalidRequest;

class StripePaymentGateway implements PaymentGateway
{
    const TEST_CARD_NUMBER = '4242424242424242';

    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function charge($amount, $token, $options = [])
    {
        try {
            $stripeCharge = \Stripe\Charge::create(array_merge([
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $token,
                'description' => 'Passion Students',
                'statement_descriptor' => 'Passion Students',
            ], $options), ['api_key' => $this->apiKey]);

            return new Charge([
                'source' => 'stripe',
                'identifier' => $stripeCharge['id'],
                'amount' => $stripeCharge['amount'],
                'card_last_four' => $stripeCharge['source']['last4'],
                'card_brand' => $stripeCharge['source']['brand'],
            ]);
        } catch (InvalidRequest $e) {
            throw new PaymentFailedException($e->getMessage());
        }
    }

    public function getValidTestToken($cardNumber = self::TEST_CARD_NUMBER)
    {
        return \Stripe\Token::create([
            'card' => [
                'number' => $cardNumber,
                'exp_month' => 1,
                'exp_year' => date('Y') + 1,
                'cvc' => '123',
            ],
        ], ['api_key' => $this->apiKey])->id;
    }
}
