<?php

namespace Tests\Feature;

use App\Item;
use App\Room;
use App\Hotel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoomsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_can_filter_rooms_by_organization()
    {
        $user = $this->signIn();

        $room = factory('App\Room')->create([
            'organization_id' => factory('App\Organization')->create([
                'church_id' => factory('App\Church')->create([
                    'name' => 'my-church'
                ])->id
            ])->id,
        ]);

        factory('App\Room')->create([
            'organization_id' => factory('App\Organization')->create([
                'church_id' => factory('App\Church')->create([
                    'name' => 'not-my-church'
                ])->id
            ])->id,
        ]);

        $response = $this->json('GET', '/admin/rooms?organization=' . $room->organization_id);

        $response->assertStatus(200);
        $response->assertSee('my-church');
        $response->assertDontSee('not-my-church');
    }

    /** @test */
    function it_can_filter_rooms_by_hotel()
    {
        $user = $this->signIn();

        $room = factory('App\Room')->create([
            'hotel_id' => factory('App\Hotel')->create([
                'name' => 'my-hotel'
            ])->id,
        ]);

        factory('App\Room')->create([
            'hotel_id' => factory('App\Hotel')->create([
                'name' => 'not-my-hotel'
            ])->id,
        ]);

        $response = $this->json('GET', '/admin/rooms?hotel=' . $room->hotel_id);

        $response->assertStatus(200);
        $response->assertSee('my-hotel');
        $response->assertDontSee('not-my-hotel');
    }
}
