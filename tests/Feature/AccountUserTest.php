<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_add_another_user_to_its_organization()
    {
        $authUser = factory(User::class)->state('churchAdmin')->create();

        $response = $this->actingAs($authUser)->post(route('account.users.store'), [
            'email' => 'new-user@example.com',
            'first_name' => 'New',
            'last_name' => 'User',
        ]);

        $response->assertRedirect(route('account.settings'));
        $user = User::where('email', 'new-user@example.com')->first();
        $this->assertTrue($user->organization->exists);
        $this->assertTrue($user->organization->is($authUser->organization));
        $this->assertEquals('new-user@example.com', $user->email);
        $this->assertEquals('New', $user->person->first_name);
        $this->assertEquals('User', $user->person->last_name);
    }
}
