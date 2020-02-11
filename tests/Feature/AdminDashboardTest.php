<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_be_reached_by_super_admin()
    {
        $user = factory(\App\User::class)->states('superAdmin')->create()->load('person');

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
    }

    /** @test */
    public function it_cannot_be_reached_by_church_admin()
    {
        $user = factory(\App\User::class)->states('churchAdmin')->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect('/');
    }

    /** @test */
    public function it_cannot_be_reached_without_signing_in()
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }
}
