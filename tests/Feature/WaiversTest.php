<?php

namespace Tests\Feature;

use Mockery;
use App\Order;
use App\Waiver;
use Carbon\Carbon;
use Tests\TestCase;
use App\Contracts\EsignProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use App\Jobs\Waiver\FetchAndUpdateStatus;
use Facades\App\Services\Esign\ProviderFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WaiversTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->esign = Mockery::mock(EsignProvider::class);

        ProviderFactory::shouldReceive('make')
            ->with('adobesign')
            ->andReturn($this->esign);
    }

    /** @test */
    public function a_waiver_request_can_be_created()
    {
        $order = factory(Order::class)->create();
        $ticket = $order->tickets()->save(
            factory(\App\Ticket::class)->make()
        );

        $this->esign->shouldReceive('createSignatureRequest')
            ->with(Mockery::hasKey('documentCreationInfo'))
            ->once();

        $this->actingAs($order->user)
            ->json('POST', "/tickets/{$ticket->id}/waivers", [
                'provider' => 'adobesign',
            ])
            ->assertStatus(201);

        $this->assertCount(1, $ticket->waivers);
        $this->assertCount(1, Waiver::all());
    }

    // /** @test */
    // public function it_can_send_a_reminder_for_an_existing_request()
    // {
    //     $waiver = factory(\App\Waiver::class)->create([
    //         'provider' => 'adobesign',
    //     ]);

    //     $this->esign->shouldReceive('sendReminder')
    //         ->once()
    //         ->with($waiver->provider_agreement_id)
    //         ->andReturn('reminder-sent');

    //     Carbon::setTestNow('+2 days');

    //     $this->actingAs($waiver->ticket->order->user)
    //         ->json('POST', "/waivers/{$waiver->id}/reminder")
    //         ->assertStatus(201);
    // }

    /** @test */
    public function it_can_delete_an_existing_request()
    {
        $order = factory(Order::class)->create();
        $ticket = $order->tickets()->save(
            factory(\App\Ticket::class)->make()
        );
        $waiver = $ticket->waiver()->save(
            factory(\App\Waiver::class)->create(['provider' => 'adobesign'])
        );

        $this->esign->shouldReceive('cancelSignatureRequest')
            ->once()
            ->with($waiver->provider_agreement_id)
            ->andReturnNull();

        $this->actingAs($order->user)
            ->json('DELETE', "/waivers/{$waiver->id}")
            ->assertStatus(204);

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

        $response->assertStatus(200);
        Queue::assertPushed(FetchAndUpdateStatus::class);
    }

    /** @test */
    public function it_can_update_status_and_download_file()
    {
        Storage::fake('dropbox');

        $order = factory(Order::class)->create();
        $ticket = $order->tickets()->save(
            factory(\App\Ticket::class)->make()
        );
        $waiver = $ticket->waiver()->save(
            factory(\App\Waiver::class)->make(['provider' => 'adobesign'])
        );
        $admin = factory(\App\User::class)->create([
            'access' => 100,
        ]);

        $this->esign->shouldReceive('fetchStatus')
            ->once()
            ->with($waiver->provider_agreement_id)
            ->andReturn('complete');

        $this->esign->shouldReceive('fetchPdf')
            ->once()
            ->with($waiver->provider_agreement_id)
            ->andReturn('Hello World');

        $this->actingAs($admin)->post("/waivers/{$waiver->id}/refresh");
        $this->assertEquals('complete', $waiver->fresh()->status);
        Storage::disk('dropbox')->assertExists($waiver->dropboxFilePath());
        Storage::disk('dropbox')->delete($waiver->dropboxFilePath());
    }
}
