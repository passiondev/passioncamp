<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate:fresh');

        (new \StudentsSeeder)->run();
    }

    public function testExample()
    {
        $this->assertTrue(true);
    }
}
