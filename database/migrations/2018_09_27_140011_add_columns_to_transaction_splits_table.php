<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTransactionSplitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_splits', function (Blueprint $table) {
            $table->string('source')->nullable();
            $table->string('identifier')->nullable();
            $table->string('cc_brand')->nullable();
            $table->string('cc_last4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_splits', function (Blueprint $table) {
            //
        });
    }
}
