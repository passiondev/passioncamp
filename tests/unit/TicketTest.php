<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
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

        dd($ticket->activity->toArray());

        $this->assertCount(3, $ticket->activity()->get());
    }
}
