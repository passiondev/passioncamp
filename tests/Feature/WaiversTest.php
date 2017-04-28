<?php

namespace Tests\Feature;

use App\Waiver;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Jobs\Waiver\AdobeSign\RequestWaiverSignature;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WaiversTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_waiver_request_can_be_created()
    {
        Queue::fake();
        $ticket = factory('App\Ticket')->create();

        $response = $this->json('POST', "/tickets/{$ticket->id}/waivers", [
            'provider' => 'adobesign'
        ]);

        $response->assertStatus(201)->assertJson([
            'status' => 'new'
        ]);
        $this->assertCount(1, $ticket->waivers);
        $this->assertCount(1, Waiver::all());
        Queue::assertPushed(RequestWaiverSignature::class, function ($job) use ($ticket) {
            return $job->waiver->id === $ticket->waiver->id;
        });
    }

    /** @test */
    function it_can_send_a_reminder_for_an_existing_request()
    {

    }

    /** @test */
    function it_can_delete_an_existing_request()
    {

    }

    /** @test */
    function it_updates_on_callback_from_adobe()
    {
        Event::fake();
        $waiver = factory('App\Waiver')->create([
            'status' => 'new'
        ]);

        $response = $this->json('get', '/webhooks/adobesign', [
            'agreementId' => $waiver->provider_agreement_id,
            'status' => 'OUT_FOR_SIGNATURE'
        ]);

        $response->assertStatus(204);
        $this->assertEquals('OUT_FOR_SIGNATURE', $waiver->fresh()->getAttributes()['status']);
    }
}
