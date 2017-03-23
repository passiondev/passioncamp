<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketTest extends TestCase
{
    /** @test */
    function the_one_where()
    {
        $ticket = new \App\Ticket([
            'agegroup' => 'student'
        ]);
        $ticket->setRelation('person', new \App\Person([
            'first_name' => 'Matt',
            'gender' => 'M',
        ]));

        $this->assertEquals('student', $ticket->agegroup);
        $this->assertEquals('Matt', $ticket->first_name);
        // $this->assertEquals('M', $ticket->person->gender);
        $this->assertTrue($ticket->relationLoaded('person'));
        $this->assertEquals('M', $ticket->gender);
        $this->assertNull($ticket->nullAttribute);
    }
}
