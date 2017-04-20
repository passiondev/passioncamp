<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminDashboardTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_can_be_reached_by_super_admin()
    {
        $user = factory('App\User')->states('superAdmin')->create()->load('person');

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
    }

    /** @test */
    function it_cant_be_reached_by_church_admin()
    {
        $user = factory('App\User')->states('churchAdmin')->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(401);
    }

    /** @test */
    function it_cant_be_reached_without_signing_in()
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
    }
}
