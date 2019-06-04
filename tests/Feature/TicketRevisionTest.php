<?php

namespace Tests\Feature;

use App\Room;
use App\Ticket;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketRevisionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_()
    {
        $room1 = factory(Room::class)->create();
        $ticket = factory(Ticket::class)->create();
        $ticket->revision();

        $ticket->rooms()->attach($room1);
        $ticket->refresh();

        sleep(1);
        $activity = $ticket->revision();
        $this->assertTrue($activity->propertiesHaveChanged());
        $this->assertArrayHasKey('roomId', $activity->properties['old']);

        $ticket->rooms()->attach(factory(Room::class)->create());
        $ticket->refresh();

        sleep(1);
        $activity = $ticket->revision();
        $this->assertTrue($activity->propertiesHaveChanged());
        $this->assertEquals(['roomId' => $room1->getKey()], $activity->properties['old']);
    }
}
