<?php

use App\Person;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PersonTest extends BrowserKitTestCase
{
    public function test_a_birthdate_can_be_set()
    {
        $person = new Person;

        $person->birthdate = '1986-3-29';
        $this->assertEquals('1986-03-29', $person->birthdate->toDateString(), '1986-3-29');

        $person->birthdate = '3/29/86';
        $this->assertEquals('1986-03-29', $person->birthdate->toDateString(), '3/29/86');

        $person->birthdate = '3/29/1986';
        $this->assertEquals('1986-03-29', $person->birthdate->toDateString(), '3/29/1986');

        $person->birthdate = '03/29/1986';
        $this->assertEquals('1986-03-29', $person->birthdate->toDateString(), '03/29/1986');

        $person->birthdate = '3/4/1986';
        $this->assertEquals('1986-03-04', $person->birthdate->toDateString(), '3/4/1986');

        $person->birthdate = 'March 29, 1986';
        $this->assertEquals('1986-03-29', $person->birthdate->toDateString(), 'March 29, 1986');

        $person->birthdate = 'Mar 29, 1986';
        $this->assertEquals('1986-03-29', $person->birthdate->toDateString(), 'Mar 29, 1986');

        $person->birthdate = 'Mar 29 86';
        $this->assertEquals('1986-03-29', $person->birthdate->toDateString(), 'Mar 29 86');

        $person->birthdate = '3.29.86';
        $this->assertEquals('1986-03-29', $person->birthdate->toDateString(), '3.29.86');

        $person->birthdate = '3-29-86';
        $this->assertEquals('1986-03-29', $person->birthdate->toDateString(), '3-29-86');

        $person->birthdate = new \Carbon\Carbon('1986-03-29');
        $this->assertEquals('1986-03-29', $person->birthdate->toDateString(), 'carbon');
    }
}
