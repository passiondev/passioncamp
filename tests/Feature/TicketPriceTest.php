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
        $org = factory(Organization::class)->create(['slug' => 'ww2019ms']);

        $response = $this->get('/api/ticket-price?event=ww2019ms&num_tickets=1&code=newyear');

        dd($response->json());
    }
}
