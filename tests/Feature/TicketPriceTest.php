<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketPriceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $org = factory(Organization::class)->create(['slug' => 'pcc']);

        $response = $this->get('/api/ticket-price?num_tickets=1&code=newyear');

        dd($response->json());
    }
}
