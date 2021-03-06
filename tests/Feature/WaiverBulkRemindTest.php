<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Access\AuthorizationException;
use App\Organization;
use App\User;

class WaiverBulkRemindTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_admin()
    {
        $user = factory(User::class)->state('superAdmin')->create();
        $organization = factory(Organization::class)->create();

        $this
            ->actingAs($user)
            ->post("/waivers/bulk-remind?organization={$organization->id}")
            ->assertRedirect()
            ->with('success');
    }

    /** @test */
    public function it_allows_church_organizer()
    {
        $organization = factory(Organization::class)->create();
        $user = factory(User::class)->state('churchAdmin')->create([
            'organization_id' => $organization,
        ]);

        $this
            ->actingAs($user)
            ->post("/waivers/bulk-remind?organization={$organization->id}")
            ->assertRedirect()
            ->with('success');
    }

    /** @test */
    public function it_doesnt_allow_other_authenticated_users()
    {
        $this->withoutExceptionHandling([AuthorizationException::class]);

        $organization = factory(Organization::class)->create();
        $user = factory(User::class)->state('churchAdmin')->create([
            'organization_id' => factory(Organization::class)->create(),
        ]);

        $this
            ->actingAs($user)
            ->post("/waivers/bulk-remind?organization={$organization->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function it_shows_bulk_remind_action_for_super_admin_when_an_organization_is_chosen()
    {
        $user = factory(User::class)->state('superAdmin')->create();
        $organization = factory(Organization::class)->create();

        $this
            ->actingAs($user)
            ->get("/waivers")
            ->assertDontSee('Remind all');

        $this
            ->actingAs($user)
            ->get("/waivers?organization={$organization}")
            ->assertOk()
            ->assertSee('Send all');
    }

    /** @test */
    public function it_shows_bulk_remind_action_for_church_organizer()
    {
        $this->app['config']->set('passioncamp.waiver_test_mode', false);
        $organization = factory(Organization::class)->create();
        $user = factory(User::class)->state('churchAdmin')->create([
            'organization_id' => $organization,
        ]);

        $this
            ->actingAs($user)
            ->get("/waivers")
            ->assertOk()
            ->assertSee('Remind all');
    }
}
