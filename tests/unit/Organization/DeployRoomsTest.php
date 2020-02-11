<?php

namespace Tests\Unit\Organization;

use App\Item;
use App\Jobs\Organization\DeployRooms;
use App\Organization;
use App\OrgItem;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class DeployRoomsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organization = factory(Organization::class)->create();
        $this->hotel = factory(Item::class)->create([
            'type' => 'hotel',
        ]);
        $this->hotel2 = factory(Item::class)->create([
            'type' => 'hotel',
        ]);
    }

    /** @test */
    public function can_add_rooms()
    {
        Event::fake();

        $this->organization->items()->save(
            factory(OrgItem::class)->make([
                'item_id' => $this->hotel->id,
                'org_type' => 'hotel',
                'quantity' => 10,
            ])
        );

        (new DeployRooms($this->organization))->handle();

        $this->assertEquals(10, $this->organization->rooms()->count());
    }

    /** @test */
    public function can_delete_rooms()
    {
        Event::fake();

        Collection::times(7, function ($number) {
            $this->organization->rooms()->create([
                'hotel_id' => $this->hotel->id,
            ]);
        });

        $this->assertEquals(7, $this->organization->rooms()->count());

        (new DeployRooms($this->organization))->handle();

        $this->assertEquals(0, $this->organization->rooms()->count());
    }

    /** @test */
    public function can_add_and_delete_rooms()
    {
        $this->markTestIncomplete();
        $this->organization->items()->save(
            factory(OrgItem::class)->make([
                'item_id' => $this->hotel->id,
                'org_type' => 'hotel',
                'quantity' => 10,
            ])
        );

        Collection::times(7, function () {
            $this->organization->rooms()->create([
                'hotel_id' => $this->hotel2->id,
            ]);
        });

        $this->assertEquals(7, $this->organization->rooms()->count());

        (new DeployRooms($this->organization))->handle();

        $this->assertEquals(10, $this->organization->rooms()->count());
    }
}
