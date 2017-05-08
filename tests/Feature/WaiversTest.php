<?php

namespace Tests\Feature;

use Mockery;
use App\Waiver;
use Carbon\Carbon;
use Tests\TestCase;
use App\Contracts\EsignProvider;
use Facades\App\Services\Esign\ProviderFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WaiversTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->esign = Mockery::mock(EsignProvider::class);

        ProviderFactory::shouldReceive('make')
            ->with('adobesign')
            ->andReturn($this->esign);
    }

    /** @test */
    function a_waiver_request_can_be_created()
    {
        $ticket = factory('App\Ticket')->create();

        $this->esign->shouldReceive('createSignatureRequest')
            ->with(Mockery::hasKey('documentCreationInfo'))
            ->once();

        $this->actingAs($ticket->order->user)
            ->json('POST', "/tickets/{$ticket->id}/waivers", [
                'provider' => 'adobesign'
            ])
            ->assertStatus(201);

        $this->assertCount(1, $ticket->waivers);
        $this->assertCount(1, Waiver::all());
    }

    /** @test */
    function it_can_send_a_reminder_for_an_existing_request()
    {
        $waiver = factory('App\Waiver')->create([
            'provider' => 'adobesign'
        ]);

        $this->esign->shouldReceive('sendReminder')
            ->once()
            ->with($waiver->provider_agreement_id)
            ->andReturn('reminder-sent');

        Carbon::setTestNow('+2 days');

        $this->actingAs($waiver->ticket->order->user)
            ->json('POST', "/waivers/{$waiver->id}/reminder")
            ->assertStatus(201);
    }

    /** @test */
    function it_can_delete_an_existing_request()
    {
        $waiver = factory('App\Waiver')->create([
            'provider' => 'adobesign'
        ]);

        $this->esign->shouldReceive('cancelSignatureRequest')
            ->once()
            ->with($waiver->provider_agreement_id)
            ->andReturnNull();

        $this->actingAs($waiver->ticket->order->user)
            ->json('DELETE', "/waivers/{$waiver->id}")
            ->assertStatus(204);

        $this->assertCount(0, Waiver::all());
    }

    /** @test */
    function it_updates_on_callback_from_adobe()
    {
        $waiver = factory('App\Waiver')->create([
            'status' => 'new'
        ]);

        $response = $this->json('get', '/webhooks/adobesign', [
            'agreementId' => $waiver->provider_agreement_id,
            'status' => 'OUT_FOR_SIGNATURE'
        ]);

        $response->assertStatus(200);

        $this->assertEquals('OUT_FOR_SIGNATURE', $waiver->fresh()->getAttributes()['status']);
    }
}
