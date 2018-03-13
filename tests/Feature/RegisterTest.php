<?php

namespace Tests\Feature;

use App\Order;
use App\Ticket;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RegisterController;
use App\User;

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
        return $this->json('POST', route('register.create'), array_merge([
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
            'tickets' => [
                1 => [
                    'first_name' => 'One',
                    'last_name' => 'Floyd',
                    'shirtsize' => 'required',
                    'gender' => 'required',
                    'grade' => 'required',
                    'birthdate' => '03/29/1986',
                    'considerations' => [
                        'nut' => 'nut',
                        'other' => 'other_text'
                    ]
                ],
                2 => [
                    'first_name' => 'Two',
                    'last_name' => 'Floyd',
                    'shirtsize' => 'required',
                    'gender' => 'required',
                    'grade' => 'required',
                    'birthdate' => '03/29/1986',
                ],
            ],
            'num_tickets' => 2,
            'stripeToken' => $this->generateToken(),
            'payment_type' => 'full',
            'fund_amount' => 50
        ], $params));
    }

    private function orderTotal()
    {
        return 100 * (app(RegisterController::class)->getCurrentTicketPrice() * 2 + 50);
    }

    /** @test */
    public function can_register()
    {
        // $this->withoutExceptionHandling();
        // \App\User::create([
        //     'email' => 'matt.floyd@268generation.com',
        //     'person' => ['first_name' => 'John']
        // ]);

        $response = $this->register([
            // 'contact' => ['email' => 'matt@example.com']
        ]);

        $response->assertRedirect(route('register.confirmation'));

        $this->assertEquals(1, Order::count());
        $this->assertEquals(1, User::count());
        $this->assertEquals(2, Ticket::count());

        $order = Order::first();
        $this->assertCount(2, $order->tickets);
        $this->assertCount(1, $order->donations);
        $this->assertCount(1, $order->transactions);
        $this->assertEquals(0, $order->balance);
        $this->assertEquals(37500, $order->tickets->first()->price);
        $this->assertEquals('matt.floyd@268generation.com', $order->user->email);
        $this->assertEquals('Matt Floyd', $order->user->person->name);
        $this->assertEquals('One', $order->tickets->first()->person->first_name);
        $this->assertEmpty($order->tickets->first()->person->street);
        $this->assertEquals($this->orderTotal(), Order::first()->transactions_total);
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
