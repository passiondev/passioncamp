<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Organization;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateOrganizationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_it_creates_and_associates_a_contact_record_if_it_didnt_exist()
    {
        $this->actingAs(
            factory(User::class)->states('superAdmin')->create()
        );

        $organization = factory(Organization::class)->create();
        $this->assertNull($organization->contact_id);

        $this->putJson("/admin/organizations/{$organization->id}", [
            'church' => [],
            'contact' => [],
            'student_pastor' => [],
        ])->assertRedirect("/admin/organizations/{$organization->id}")->assertSessionHas('success');

        $organization->refresh();
        $this->assertNotNull($organization->contact_id);
    }

    public function test_it_creates_and_associates_a_student_pastor_record_if_it_didnt_exist()
    {
        $this->actingAs(
            factory(User::class)->states('superAdmin')->create()
        );

        $organization = factory(Organization::class)->create();
        $this->assertNull($organization->student_pastor_id);

        $this->putJson("/admin/organizations/{$organization->id}", [
            'church' => [],
            'contact' => [],
            'student_pastor' => [],
        ])->assertRedirect("/admin/organizations/{$organization->id}")->assertSessionHas('success');

        $organization->refresh();
        $this->assertNotNull($organization->student_pastor_id);
    }
}
