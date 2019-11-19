<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Item;
use Illuminate\Support\Facades\Event;
use App\Events\OrgItemSaved;

class OrganizationItemTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->organization = factory(\App\Organization::class)->create();
        factory(\App\Item::class)->create(['type' => 'ticket']);

        $user = factory(\App\User::class)->create([
            'email' => 'matt.floyd@268generation.com',
            'access' => 100,
        ]);
        $this->actingAs($user);
    }

    /** @test */
    function test_an_item_can_be_added_to_an_organization()
    {
        Event::fake();

        $response = $this->post(route('admin.organizations.items.store', ['organization' => $this->organization]), [
            'item' => Item::first()->id,
            'quantity' => 1,
            'notes' => 'notes test',
            'cost' => '99'
        ]);

        // redirects to org
        $response->assertRedirect(route('admin.organizations.show', ['organization' => $this->organization]));

        // org has 1 item with above info
        $this->assertCount(1, $this->organization->items()->get());

        // has org_type and item associated
        $item = $this->organization->items()->first();
        $this->assertEquals('ticket', $item->org_type);
        $this->assertEquals(Item::first()->id, $item->item_id);
        $this->assertEquals(1, $item->quantity);
        $this->assertEquals(9900, $item->cost);
        $this->assertEquals('notes test', $item->notes);

        // event fired
        Event::assertDispatched(OrgItemSaved::class);
    }
}
