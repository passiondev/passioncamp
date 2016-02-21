<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
    // use DatabaseTransactions;

    public function test_can_register()
    {
        $this->disableExceptionHandling();

        $this->post('/register', [
            'contact' => [
                'first_name' => 'Matt',
                'last_name' => 'Floyd',
                'email' => 'matt.floyd@268generation.com',
                'phone' => '7062240124',
            ],
            'num_tickets' => 3,
            'tickets' => [
                [
                    'first_name' => 'Kincey1',
                    'last_name' => 'Floyd',
                    'shirt_size' => 'M',
                    'gender' => 'F',
                    'grade' => '11',
                    'birthdate' => '1987-02-27'
                ],
                [
                    'first_name' => 'Kincey2',
                    'last_name' => 'Floyd',
                    'shirt_size' => 'M',
                    'gender' => 'F',
                    'grade' => '11',
                    'birthdate' => '1987-02-27'
                ],
                [
                    'first_name' => 'Kincey3',
                    'last_name' => 'Floyd',
                    'shirt_size' => 'M',
                    'gender' => 'F',
                    'grade' => '11',
                    'birthdate' => '1987-02-27'
                ]
            ],
            'stripeToken' => $this->generateToken(),
            'payment_amount_type' => 'deposit'
        ]);

        $this->assertResponseStatus(200);
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
}
