<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class RegisterTest extends DuskTestCase
{
    /** @test */
    public function it_shows_error_messages()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->fillStripeElement('#card-element iframe')
                    ->press('Submit Registration')
                    ->waitFor('#form-submission-alert')
                    ->assertSee('There was an error with your submission.');
        });
    }

    /** @test */
    public function tickets_can_be_added()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('#num_tickets', '2')
                    ->assertSee('Student #2');
        });
    }
}
