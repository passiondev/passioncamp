<?php

namespace Tests\Feature;

use App\Order;
use App\Ticket;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        factory(\App\Organization::class)->create(['slug' => 'pcc']);
    }

    private function register($params = [])
    {
        return $this->post('/register', array_merge([
            'contact' => [
                'first_name' => 'Matt',
                'last_name' => 'Floyd',
                'email' => 'matt.floyd@268generation.com',
                'phone' => '7062240124',
            ],
            'billing' => [
                'street' => '3180 windfield cir',
                'city' => 'tucker',
                'state' => 'ga',
                'zip' => '30084',
            ],
            'num_tickets' => 3,
            'stripeToken' => $this->generateToken(),
            'payment_amount_type' => 'deposit',
            'fund_amount' => 50
        ], $params));
    }

    /** @test */
    public function can_register()
    {
        $response = $this->register();

        $response->assertRedirect(route('register.confirmation'));
        $this->assertEquals(1, Order::count());
        $this->assertEquals(3, Ticket::count());
    }

    /** @test */
    function order_is_not_created_if_payment_fails()
    {
        $response = $this->register([
            'stripeToken' => 'invalid-token'
        ]);

        $response->assertRedirect(route('register.create'));
        $this->assertEquals(0, Order::count());
        $this->assertEquals(0, Ticket::count());
    }

    private function generateToken()
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
