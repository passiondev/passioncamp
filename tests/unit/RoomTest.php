<?php

namespace Tests\Unit;

use App\Room;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoomTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_stores_activity_when_revisioned()
    {
        $room = factory(\App\Room::class)->create([
            'description' => 'description',
            'notes' => 'notes',
        ]);

        Carbon::setTestNow('+5 minutes');

        $room
            ->hotel()->associate(factory(\App\Hotel::class)->create())
            ->update([
                'name' => 'Room 2'
            ]);
        $room->fresh()->revision();

        Carbon::setTestNow('+5 minutes');

        $room->update([
            'name' => 'Room 3',
            'description' => 'description 2'
        ]);

        $room->fresh()->revision();
        $room->fresh()->revision();

        dd(collect(['name', 'description', 'notes', 'hotelName'])->flip()->transform(function ($item) {
            return null;
        })->merge($room->activity()->latest()->first()->changes['old']));

        dd($room->activity->toArray());

        $this->assertCount(4, $room->activity()->get());
    }
}
