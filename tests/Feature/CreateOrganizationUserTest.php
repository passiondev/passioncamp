<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Organization;
use App\Mail\AccountUserCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateOrganizationUserTest extends TestCase
{
    use RefreshDatabase;

    protected $organization;

    public function setUp()
    {
        parent::setUp();

        $this->actingAs(
            factory(User::class)->states('superAdmin')->create()
        );

        $this->organization = factory(Organization::class)->create();
    }

    /** @test */
    public function a_new_user_can_be_added_to_an_organization()
    {
        $this->post("/admin/organizations/{$this->organization->id}/users", [
            'email' => 'email@example.com',
        ]);

        $this->assertEquals(1, $this->organization->users()->where('email', 'email@example.com')->count());
    }

    /** @test */
    public function an_existing_user_can_be_added_to_an_organization()
    {
        $user = factory(User::class)->create([
            'email' => 'email@example.com',
        ]);

        $this->post("/admin/organizations/{$this->organization->id}/users", [
            'email' => 'email@example.com',
        ]);

        $user->refresh();

        $this->assertTrue($this->organization->users->contains($user), 'organization contains user');
        $this->assertTrue($user->organization->is($this->organization));
    }

    /** @test */
    public function added_user_is_made_a_church_admin()
    {
        $this->post("/admin/organizations/{$this->organization->id}/users", [
            'email' => 'email@example.com',
        ]);

        $user = User::where('email', 'email@example.com')->first();
        $this->assertTrue($user->isChurchAdmin());
    }

    /** @test */
    public function it_sends_an_email_to_a_new_user()
    {
        Mail::fake();

        $this->post("/admin/organizations/{$this->organization->id}/users", [
            'email' => 'email@example.com',
        ]);

        Mail::assertQueued(AccountUserCreated::class, function ($mailable) {
            return $mailable->user->is(
                $this->organization->users()->where('email', 'email@example.com')->first()
            );
        });
    }

    /** @test */
    public function it_doesnt_send_an_email_to_an_existing_user()
    {
        Mail::fake();

        $user = factory(User::class)->states('churchAdmin')->create([
            'email' => 'email@example.com',
        ]);

        $this->post("/admin/organizations/{$this->organization->id}/users", [
            'email' => 'email@example.com',
        ]);

        Mail::assertNotQueued(AccountUserCreated::class);
    }
}
