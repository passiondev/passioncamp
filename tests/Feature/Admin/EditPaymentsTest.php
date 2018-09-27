<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Transaction;
use App\TransactionSplit;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditPaymentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function transaction_splits_table_has_migrated_columns()
    {
        factory(TransactionSplit::class)->create([
            'source' => 'stripe',
            'identifier' => '11112222333344445555',
            'cc_brand' => 'Visa',
            'cc_last4' => '8765',
        ]);

        $this->assertDatabaseHas('transaction_splits', [
            'source' => 'stripe',
            'identifier' => '11112222333344445555',
            'cc_brand' => 'Visa',
            'cc_last4' => '8765',
        ]);
    }

    /** @test */
    public function transaction_data_has_been_migrated()
    {
        factory(TransactionSplit::class)->create([
            'transaction_id' => factory(Transaction::class)->create(),
            'source' => null,
            'identifier' => null,
            'cc_brand' => null,
            'cc_last4' => null,
        ]);

        Transaction::migrateAllDataToSplits();
    }
}
