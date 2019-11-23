<?php

namespace Tests\Unit;

use Mockery;
use Carbon\Carbon;
use Tests\TestCase;
use App\Contracts\EsignProvider;
use Illuminate\Support\Facades\Event;
use Facades\App\Services\Esign\ProviderFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TicketTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_stores_activity_when_revisioned()
    {
        $ticket = factory(\App\Ticket::class)->create();
        Carbon::setTestNow('+5 minutes');

        $ticket->person->update([
            'first_name' => 'new-name',
        ]);
        $ticket->fresh()->revision();

        Carbon::setTestNow('+5 minutes');

        $ticket->rooms()->save(
            factory(\App\Room::class)->make()
        );
        $ticket->fresh()->revision();

        Carbon::setTestNow('+5 minutes');
        $ticket->fresh()->revision();

        // dd($ticket->activity()->latest()->first()->changes);

        $this->assertCount(3, $ticket->activities()->get());
    }

    /** @test */
    public function it_fires_canceling_event()
    {
        Event::fake();

        $ticket = factory(\App\Ticket::class)->create();
        $ticket->cancel();

        Event::assertDispatched('eloquent.created: App\Ticket');
        Event::assertDispatched('eloquent.canceled: App\Ticket');
    }

    /** @test */
    public function it_cancels_waiver_request_when_being_canceled()
    {
        $waiver = factory(\App\Waiver::class)->create([
            'provider' => 'adobesign',
        ]);
        $this->assertInstanceOf(\App\Ticket::class, $ticket = $waiver->fresh()->ticket);

        $esign = Mockery::mock(EsignProvider::class);

        ProviderFactory::shouldReceive('make')
            ->with('adobesign')
            ->andReturn($esign);

        $esign->shouldReceive('cancelSignatureRequest')
            ->once()
            ->with($waiver->provider_agreement_id)
            ->andReturnNull();

        $ticket->cancel();
    }

    /** @test */
    public function it_cancels_waiver_request_when_being_deleted()
    {
        $waiver = factory(\App\Waiver::class)->create([
            'provider' => 'adobesign',
        ]);
        $this->assertInstanceOf(\App\Ticket::class, $ticket = $waiver->fresh()->ticket);

        $esign = Mockery::mock(EsignProvider::class);

        ProviderFactory::shouldReceive('make')
            ->with('adobesign')
            ->andReturn($esign);

        $esign->shouldReceive('cancelSignatureRequest')
            ->once()
            ->with($waiver->provider_agreement_id)
            ->andReturnNull();

        $ticket->delete();
    }
}
