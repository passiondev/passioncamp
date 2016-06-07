<?php

use App\User;
use App\Organization;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_creating_an_admin()
    {
        $organization = Organization::find(8);

        $organization->authUsers()->save(
            User::make()
                ->setAttribute('username', 'test')
                ->setAttribute('access', 1)
        );

        dd($organization->authUsers);
    }
}
