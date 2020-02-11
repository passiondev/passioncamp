<?php

namespace Tests\Feature\Admin;

use App\Organization;
use App\Transaction;
use App\TransactionSplit;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditPaymentsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function an_admin_can_edit_a_payment()
    {
        // create organization and add a payment
        $organization = factory(Organization::class)->create();
        $transaction = $organization->transactions()->create(factory(TransactionSplit::class)->raw());

        $this->actingAs(
            factory(User::class)->states('superAdmin')->create()
        );

        // view edit form
        $this->get("/admin/organizations/{$organization->id}/payments/{$transaction->id}/edit")->assertSuccessful();
    }

    /** @test */
    public function a_payment_can_be_updated()
    {
        $organization = factory(Organization::class)->create();
        $transaction = $organization->transactions()->create(factory(TransactionSplit::class)->raw());

        $this->actingAs(
            factory(User::class)->states('superAdmin')->create()
        );

        $this->put("/admin/organizations/{$organization->id}/payments/{$transaction->id}", [
            'source' => 'stripe',
            'identifier' => 'EV5xl2AqQ1Rhh2pwsSHHL3FH',
            'amount' => $amount = $this->faker->numberBetween(1, 999),
        ])->assertRedirect("/admin/organizations/{$organization->id}");

        $this->assertDatabaseHas('transaction_splits', [
            'amount' => $amount * 100,
            'organization_id' => $organization->id,
        ]);

        $this->assertDatabaseHas('transactions', [
            'amount' => $amount * 100,
            'source' => 'stripe',
            'identifier' => 'EV5xl2AqQ1Rhh2pwsSHHL3FH',
        ]);
    }

    /** @test */
    public function a_payment_can_be_deleted()
    {
        $organization = factory(Organization::class)->create();
        $transaction = $organization->transactions()->create(factory(TransactionSplit::class)->raw());

        $this->actingAs(
            factory(User::class)->states('superAdmin')->create()
        );

        $this->assertCount(1, TransactionSplit::all());
        $this->assertCount(1, Transaction::all());
        $this->assertCount(1, $organization->transactions()->get());

        $this->delete("/admin/organizations/{$organization->id}/payments/{$transaction->id}")
            ->assertRedirect("/admin/organizations/{$organization->id}");

        $this->assertCount(0, TransactionSplit::all());
        $this->assertCount(0, Transaction::all());
        $this->assertCount(0, $organization->transactions()->get());
    }
}
