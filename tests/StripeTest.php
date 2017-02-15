<?php

use Omnipay\Omnipay;
use App\Organization;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StripeTest extends TestCase
{
    protected $organization;
    protected $token;

    public function setUp()
    {
        parent::setUp();

        $this->organization = Organization::find(8);
        $this->token = $this->generateToken();
    }

    public function generateToken()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    
        $data = \Stripe\Token::create([
            'card' => [
                "number" => "4111111111111111",
                "exp_month" => 11,
                "exp_year" => date('y') + 4,
                "cvc" => "314"
            ]
        ]);

        return $data['id'];
    }

    public function test()
    {
        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey(config('services.stripe.pcc.secret'));
        // $gateway->setParameter('stripe_account', $this->organization->setting('stripe_user_id'));

        $request = $gateway->purchase([
            'amount' => number_format(10, 2, '.', ''),
            'currency' => 'USD',
            'token' => $this->token,
        ]);

        $charge = $request->send();

        dd($charge);

        $charge = \Stripe\Charge::create([
          'amount' => 1000,
          'currency' => 'usd',
          'source' => $this->token
        ], ['stripe_account' => $this->organization->setting('stripe_user_id')]);
        dd($charge);
    }
}
