<?php

namespace Tests\Unit;

use App\Contracts\EsignProvider;
use App\Jobs\Waiver\RequestWaiverSignature;
use App\Waiver;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class WaiverTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_a_provider()
    {
        Queue::fake();

        $waiver = factory(\App\Waiver::class)->create([
            'provider' => 'adobesign',
        ]);

        $this->assertInstanceOf(EsignProvider::class, $waiver->provider());
    }

    /** @test */
    public function it_has_a_dropbox_file_path()
    {
        $this->markTestIncomplete();
        $waiver = factory(\App\Waiver::class)->create();

        $this->assertNotEmpty($waiver->dropboxFilePath());
    }

    /** @test */
    public function it_creates_a_job_to_send_waiver()
    {
        Queue::fake();

        $ticket = factory(\App\Ticket::class)->create();

        $waiver = $ticket->createWaiver();

        $this->assertCount(1, Waiver::all());
        $this->assertInstanceOf(Waiver::class, $waiver);
        Queue::assertPushed(RequestWaiverSignature::class);
    }

    /** @test */
    public function it_cant_create_multiple_waivers()
    {
        Queue::fake();

        $ticket = factory(\App\Ticket::class)->create();

        $ticket->createWaiver();
        $ticket->createWaiver();

        $this->assertCount(1, Waiver::all());
    }
}
