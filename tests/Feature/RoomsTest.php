<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RoomsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_filter_rooms_by_organization()
    {
        $this->markTestSkipped();
        $user = $this->signIn();

        $room = factory(\App\Room::class)->create([
            'organization_id' => factory(\App\Organization::class)->create([
                'church_id' => factory(\App\Church::class)->create([
                    'name' => 'my-church',
                ])->id,
            ])->id,
        ]);

        factory(\App\Room::class)->create([
            'organization_id' => factory(\App\Organization::class)->create([
                'church_id' => factory(\App\Church::class)->create([
                    'name' => 'not-my-church',
                ])->id,
            ])->id,
        ]);

        $response = $this->json('GET', '/admin/rooms?organization='.$room->organization_id);

        $response->assertStatus(200);
        $response->assertSee('my-church');
        $response->assertDontSee('not-my-church');
    }

    /** @test */
    public function it_can_filter_rooms_by_hotel()
    {
        $this->markTestSkipped();
        $user = $this->signIn();

        $room = factory(\App\Room::class)->create([
            'hotel_id' => factory(\App\Hotel::class)->create([
                'name' => 'my-hotel',
            ])->id,
        ]);

        factory(\App\Room::class)->create([
            'hotel_id' => factory(\App\Hotel::class)->create([
                'name' => 'not-my-hotel',
            ])->id,
        ]);

        $response = $this->json('GET', '/admin/rooms?hotel='.$room->hotel_id);

        $response->assertStatus(200);
        $response->assertSee('my-hotel');
        $response->assertDontSee('not-my-hotel');
    }

    /** @test */
    public function it_can_check_in_a_room()
    {
        $this->markTestSkipped();
        $this->signIn();

        $room = factory(\App\Room::class)->create();

        $response = $this->json('POST', "/rooms/{$room->id}/check-in");

        $this->assertTrue($room->fresh()->is_checked_in);
    }

    /** @test */
    public function it_can_mark_a_key_received()
    {
        $this->markTestSkipped();
        $this->signIn();

        $room = factory(\App\Room::class)->create();

        $response = $this->json('POST', "/rooms/{$room->id}/key-received");

        $this->assertTrue($room->fresh()->is_key_received);
    }
}
