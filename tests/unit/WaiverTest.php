<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Contracts\EsignProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WaiverTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_has_a_provider()
    {
        Queue::fake();

        $waiver = factory('App\Waiver')->create([
            'provider' => 'adobesign'
        ]);

        $this->assertInstanceOf(EsignProvider::class, $waiver->provider());
    }

    /** @test */
    function it_has_a_dropbox_file_path()
    {
        $waiver = factory('App\Waiver')->create();

        $this->assertNotEmpty($waiver->dropboxFilePath());
    }
}
