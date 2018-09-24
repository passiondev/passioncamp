<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateOrganizationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testExample()
    {
        // sign in
        $this->actingAs(
            factory(User::class)->states('superAdmin')->create()
        );

        // create organization
        $organization = factory(Organization::class)->create();

        // submit edit form
        $this->putJson("/admin/organization/{$organization->id}", [

        ]);
        // see organization row

        // see church row

        // see contact row

        // see student pastor row
    }
}
