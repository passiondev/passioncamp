<?php

namespace Tests\Feature;

use App\User;
use App\Order;
use App\Ticket;
use App\Occurrence;
use Tests\TestCase;
use App\Organization;
use App\Mail\WaiverRequest;
use Illuminate\Support\Carbon;
use App\Billing\FakePaymentGateway;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use App\Jobs\Order\SendConfirmationEmail;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake();

        $this->paymentGateway = new FakePaymentGateway;
        $this->app->instance('stripe.pcc', $this->paymentGateway);
    }

    /** @test */
    public function can_register()
    {
        $this->register();

        $this->assertEquals(1, Order::count());
        $this->assertEquals(1, User::count());
        $this->assertEquals(2, Ticket::count());

        $order = Order::first();

        $this->assertCount(2, $order->tickets);
        $this->assertCount(1, $order->transactions);
        $this->assertEquals(0, $order->balance);
        $this->assertEquals((new Occurrence(config('occurrences.pcc')))->ticketPrice(), $order->tickets->first()->price);
        $this->assertEquals('matt.floyd@268generation.com', $order->user->email);
        $this->assertEquals('Matt Floyd', $order->user->person->name);
        $this->assertEquals('One', $order->tickets->first()->person->first_name);
        $this->assertEmpty($order->tickets->first()->person->street);
        $this->assertEquals($this->orderTotal(), Order::first()->transactions_total);
    }

    /** @test */
    public function it_sends_a_confirmation_email_to_the_user()
    {
        $this->register();

        Bus::assertDispatched(SendConfirmationEmail::class, function ($job) {
            return $job->order->is(Order::first());
        });
    }

    /** @test */
    public function it_redirects_to_confirmation_if_successful()
    {
        $response = $this->register();

        $response->assertJsonFragment(['location' => route('register.confirmation')]);
        $response->assertSessionHas('order_id', Order::first()->id);
    }

    /** @test */
    public function deposits_can_be_paid()
    {
        Carbon::setTestNow('2019-03-04');
        $this->register(['payment_type' => 'deposit', 'num_tickets' => '1', 'tickets' => []]);

        $order = Order::first();
        $this->assertEquals($order->tickets()->count() * 7500, $order->transactions_total);
    }

    /** @test */
    public function order_is_not_created_if_payment_fails()
    {
        $response = $this->register([
            'stripeToken' => $this->paymentGateway->getFailingTestToken(),
        ]);

        $response->assertJsonValidationErrors('payment');
        $this->assertEquals(0, Order::count());
        $this->assertEquals(0, Ticket::count());
    }

    /** @test */
    public function a_rep_name_can_be_added()
    {
        $response = $this->register([
            'rep' => 'Rep Name',
        ]);

        $order = Order::first();
        $this->assertEquals('Rep Name', $order->order_data->get('rep'));
    }

    /** @not-a-test */
    public function it_sends_a_waiver_email_to_each_ticket()
    {
        // Mail::fake();

        // $response = $this->register([
        //     'contact' => [
        //         'first_name' => 'Matt',
        //         'last_name' => 'Floyd',
        //         'email' => 'test-email@example.com',
        //         'phone' => '7062240124',
        //     ],
        // ]);

        // Ticket::all()->each(function ($ticket) {
        //     Mail::assertQueued(WaiverRequest::class, function ($mail) use ($ticket) {
        //         return $mail->ticket->is($ticket) &&
        //             $mail->hasTo('test-email@example.com') &&
        //             $mail->hasTo($ticket->order->user->person->email);
        //     });
        // });
    }

    private function register($params = [])
    {
        factory(Organization::class)->create(['slug' => 'pcc']);

        return $this->postJson(route('register.create'), $this->data = array_merge([
            'first_name' => 'Matt',
            'last_name' => 'Floyd',
            'email' => 'matt.floyd@268generation.com',
            'phone' => '7062240124',
            'street' => '3180 windfield cir',
            'city' => 'tucker',
            'state' => 'ga',
            'zip' => '30084',
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
                        'other' => 'other_text',
                    ],
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
            'stripeToken' => $this->paymentGateway->getValidTestToken(),
            'payment_type' => 'full',
        ], $params));
    }

    private function orderTotal()
    {
        return array_sum([
            $this->data['num_tickets'] * (new Occurrence(config('occurrences.pcc')))->ticketPrice(),
        ]);
    }
}
