<?php

namespace Tests\Feature;

use Mockery;
use App\Order;
use App\Waiver;
use Carbon\Carbon;
use Tests\TestCase;
use App\Contracts\EsignProvider;
use App\Jobs\Waiver\SendReminder;
use App\Jobs\CancelSignatureRequest;
use Illuminate\Support\Facades\Queue;
use App\Jobs\Waiver\FetchAndUpdateStatus;
use App\Jobs\Waiver\RequestWaiverSignature;
use Facades\App\Services\Esign\ProviderFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WaiversTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('passioncamp.waiver_test_mode', false);

        $this->esign = Mockery::mock(EsignProvider::class);

        ProviderFactory::shouldReceive('make')
            ->with('adobesign')
            ->andReturn($this->esign);
    }

    /** @test */
    public function a_waiver_request_can_be_created()
    {
        Queue::fake();
        $order = factory(Order::class)->create();
        $ticket = $order->tickets()->save(
            factory(\App\Ticket::class)->make()
        );

        $this->actingAs($order->user)
            ->json('POST', "/tickets/{$ticket->id}/waivers", [
                'provider' => 'adobesign',
            ])
            ->assertStatus(201);

        Queue::assertPushed(RequestWaiverSignature::class);
        $this->assertCount(1, $ticket->waivers()->get());
        $this->assertCount(1, Waiver::all());
    }

    /** @test */
    public function it_can_send_a_reminder_for_an_existing_request()
    {
        Queue::fake();

        $order = factory(Order::class)->create();
        $ticket = $order->tickets()->save(
            factory(\App\Ticket::class)->make()
        );
        $waiver = $ticket->waiver()->save(
            factory(\App\Waiver::class)->create(['provider' => 'adobesign'])
        );

        Carbon::setTestNow('+2 days');

        $this->actingAs($order->user)
            ->json('POST', "/waivers/{$waiver->id}/reminder")
            ->assertStatus(201);

        Queue::assertPushed(SendReminder::class);
    }

    /** @test */
    public function it_can_delete_an_existing_request()
    {
        Queue::fake();

        $order = factory(Order::class)->create();
        $ticket = $order->tickets()->save(
            factory(\App\Ticket::class)->make()
        );
        $waiver = $ticket->waiver()->save(
            factory(\App\Waiver::class)->create(['provider' => 'adobesign'])
        );

        $this->actingAs($order->user)
            ->json('DELETE', "/waivers/{$waiver->id}")
            ->assertStatus(204);

        Queue::assertPushed(CancelSignatureRequest::class);
        $this->assertCount(0, Waiver::all());
    }

    /** @test */
    public function it_updates_on_callback_from_adobe()
    {
        Queue::fake();

        $order = factory(Order::class)->create();
        $ticket = $order->tickets()->save(
            factory(\App\Ticket::class)->make()
        );
        $waiver = $ticket->waiver()->save(
            factory(\App\Waiver::class)->create(['provider' => 'adobesign'])
        );

        $response = $this->put("/webhooks/adobesign?documentKey={$waiver->provider_agreement_id}&eventType=ESIGNED");

        $response->assertOk();
        Queue::assertPushed(FetchAndUpdateStatus::class);
    }
}
