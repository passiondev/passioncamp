<?php

namespace Tests\Unit;

use App\Waiver;
use Tests\TestCase;
use App\Contracts\EsignProvider;
use Illuminate\Support\Facades\Queue;
use App\Jobs\Waiver\RequestWaiverSignature;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WaiverTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_has_a_provider()
    {
        Queue::fake();

        $waiver = factory(\App\Waiver::class)->create([
            'provider' => 'adobesign'
        ]);

        $this->assertInstanceOf(EsignProvider::class, $waiver->provider());
    }

    /** @test */
    function it_has_a_dropbox_file_path()
    {
        $waiver = factory(\App\Waiver::class)->create();

        $this->assertNotEmpty($waiver->dropboxFilePath());
    }

    /** @test */
    function it_creates_a_job_to_send_waiver()
    {
        Queue::fake();

        $ticket = factory(\App\Ticket::class)->create();

        $waiver = $ticket->createWaiver();

        $this->assertCount(1, Waiver::all());
        $this->assertInstanceOf(Waiver::class, $waiver);
        Queue::assertPushed(RequestWaiverSignature::class);
    }


    /** @test */
    function it_cant_create_multiple_waivers()
    {
        Queue::fake();

        $ticket = factory(\App\Ticket::class)->create();

        $ticket->createWaiver();
        $ticket->createWaiver();

        $this->assertCount(1, Waiver::all());
    }
}
