<?php

namespace Tests\Unit;

use Mockery;
use Carbon\Carbon;
use Tests\TestCase;
use App\Contracts\EsignProvider;
use Illuminate\Support\Facades\Event;
use Facades\App\Services\Esign\ProviderFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function the_one_where()
    {
        // $ticket = new \App\Ticket([
        //     'agegroup' => 'student'
        // ]);
        // $ticket->setRelation('person', new \App\Person([
        //     'first_name' => 'Matt',
        //     'gender' => 'M',
        // ]));
        // $ticket = $ticket->fresh();

        // $this->assertEquals('student', $ticket->agegroup);
        // $this->assertEquals('Matt', $ticket->first_name);
        // // $this->assertEquals('M', $ticket->person->gender);
        // $this->assertTrue($ticket->relationLoaded('person'));
        // $this->assertEquals('M', $ticket->gender);
        // $this->assertNull($ticket->nullAttribute);
    }

    /** @test */
    function it_stores_activity_when_revisioned()
    {
        $ticket = factory('App\Ticket')->create();
        Carbon::setTestNow('+5 minutes');

        $ticket->person->update([
            'first_name' => 'new-name'
        ]);
        $ticket->fresh()->revision();

        Carbon::setTestNow('+5 minutes');

        $ticket->rooms()->save(
            factory('App\Room')->make()
        );
        $ticket->fresh()->revision();

        Carbon::setTestNow('+5 minutes');
        $ticket->fresh()->revision();


        // dd($ticket->activity()->latest()->first()->changes);


        $this->assertCount(3, $ticket->activity()->get());
    }

    /** @test */
    function it_fires_canceling_event()
    {
        Event::fake();

        $ticket = factory('App\Ticket')->create();
        $ticket->cancel();

        Event::assertDispatched('eloquent.created: App\Ticket');
        Event::assertDispatched('eloquent.canceling: App\Ticket');
    }

    /** @test */
    function it_cancels_waiver_request_when_being_canceled()
    {
        $waiver = factory('App\Waiver')->create([
            'provider' => 'adobesign'
        ]);
        $this->assertInstanceOf('App\Ticket', $ticket = $waiver->fresh()->ticket);

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
    function it_cancels_waiver_request_when_being_deleted()
    {
        $waiver = factory('App\Waiver')->create([
            'provider' => 'adobesign'
        ]);
        $this->assertInstanceOf('App\Ticket', $ticket = $waiver->fresh()->ticket);

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
