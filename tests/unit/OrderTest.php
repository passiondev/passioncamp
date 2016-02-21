<?php

use App\Order;
use App\Ticket;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    private $order;
    private static $ticket_count = 40;
    private static $ticket_price = 99;

    public function setUp()
    {
        parent::setUp();

        $this->order = factory(Order::class)->create();

        factory(Ticket::class, static::$ticket_count)->make()->each(function ($ticket) {
            $ticket->price = static::$ticket_price;
            $ticket->order()->associate($this->order)->save();
        });
    }

    public function test_has_tickets()
    {
        $this->assertEquals(static::$ticket_count, $this->order->tickets->count());
        $this->assertEquals(static::$ticket_count, $this->order->items->count());
        $this->assertEquals(static::$ticket_count * static::$ticket_price, $this->order->ticket_total);
        $this->assertEquals(static::$ticket_count * static::$ticket_price, $this->order->grand_total);
    }
}
