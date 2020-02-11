<?php

namespace Tests\Unit;

use App\Occurrence;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OccurrenceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->occurrence = new Occurrence([
            'slug' => 'ww2019ms',
            'title' => 'Winter WKND (MS)',
            'title_short' => 'Winter WKND',
            'header_bg' => 'https://res.cloudinary.com/pcc/image/upload/f_auto,q_auto/v1541437660/students_registration/ww2019ms/header-bg.jpg',
            'header_fg' => 'https://res.cloudinary.com/pcc/image/upload/w_auto,dpr_auto,f_auto,q_auto/v1541437660/students_registration/ww2019ms/header-fg.png',
            'pricing' => [
                '2018-10-30' => '99',
                '2018-12-02' => '115',
                '2018-12-31' => '130',
            ],
            'closes' => '2019-01-27',
            'confirmation' => 'We are so excited your student is planning to join us for Middle School Winter WKND! We are looking forward to all that Jesus is going to do in the lives of our students during our time together!',
            'donation_list' => "- A Family Group Leader\'s spot (We will have 40-50 leaders with us.)\n- Ground Transportation (Each van costs $420 and we will have 10-12 vans!)\n- To help a student who can’t afford Winter WKND (We don’t want anyone to miss out!)",
            'grades' => [6, 7, 8],
        ]);
    }

    /** @test */
    public function lowestTicketPrice()
    {
        $this->assertEquals(9900, $this->occurrence->lowestTicketPrice());
    }

    /** @test */
    public function ticketPriceBasedOnDate()
    {
        Carbon::setTestNow('2018-11-03');
        $this->assertEquals(9900, $this->occurrence->ticketPrice());

        Carbon::setTestNow('2018-12-03');
        $this->assertEquals(11500, $this->occurrence->ticketPrice());

        Carbon::setTestNow('2019-01-03');
        $this->assertEquals(13000, $this->occurrence->ticketPrice());
    }

    /** @test */
    public function ticketPriceMultiTicket()
    {
        Carbon::setTestNow('2018-11-01');
        $this->assertEquals(9900, $this->occurrence->ticketPrice(4));

        Carbon::setTestNow('2018-12-01');
        $this->assertEquals(9900, $this->occurrence->ticketPrice(4));
    }

    /** @test */
    public function discounts_get_applied()
    {
        $this->assertEquals(13000, $this->occurrence->ticketPrice(1));

        $this->assertEquals(13000, $this->occurrence->ticketPrice(1, 'scholarship30'));
        $this->assertEquals(13000, $this->occurrence->ticketPrice(4, 'scholarship30'));

        $this->assertEquals(13000, $this->occurrence->ticketPrice(1, 'scholarship55'));
        $this->assertEquals(13000, $this->occurrence->ticketPrice(4, 'scholarship55'));

        $this->assertEquals(3000, $this->occurrence->ticketPrice(1, 'scholarship100'));
        $this->assertEquals(3000, $this->occurrence->ticketPrice(4, 'scholarship100'));

        Carbon::setTestNow('2019-01-17');
        $this->assertEquals(13000, $this->occurrence->ticketPrice(1, 'rising'));
        $this->assertEquals(13000, $this->occurrence->ticketPrice(4, 'rising'));
    }
}
