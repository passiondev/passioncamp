<?php

use App\Organization;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrganizationTest extends TestCase
{
    use DatabaseTransactions;

    protected $organization;

    public function setUp()
    {
        parent::setUp();

        $this->organization = Organization::find(8);
    }

    // public function test_fetch_setting()
    // {
    //     $setting = $this->organization->setting('stripe_access_token');

    //     $this->assertEquals('sk_test_LFUl1Vgp2RHZJ7TiB17Tg55r', $setting);
    // }

    public function test_add_setting1()
    {
        $this->organization->setting('stripe_access_token', '111');

        $setting = $this->organization->setting('stripe_access_token');

        $this->assertEquals('111', $setting);
    }

    // public function test_add_setting2()
    // {
    //     $this->organization->setting(['stripe_access_token' => '111']);

    //     $setting = $this->organization->setting('stripe_access_token');

    //     $this->assertEquals('111', $setting);
    // }
}
