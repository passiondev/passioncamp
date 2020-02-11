<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    /** @test */
    public function it_shows_error_messages()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://pccstudents.passioncamp.test/register')
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
            $browser->visit('http://pccstudents.passioncamp.test/register')
                    ->type('#num_tickets', '2')
                    ->assertSee('Student #2');
        });
    }

    /** @test */
    public function deposit_can_be_paid()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://pccstudents.passioncamp.test/register')
                ->assertSee('Pay Deposit');

            $browser->visit('http://pccstudents.passioncamp.test/register')
                ->click('@pay-deposit')
                ->assertSee('your credit card will be charged $75.00');
        });
    }
}
