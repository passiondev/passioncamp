<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionSplitsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transaction_splits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('organization_id')->nullable()->index();
            $table->unsignedInteger('order_id')->nullable()->index();
            $table->unsignedInteger('transaction_id')->nullable()->index();
            $table->integer('amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('transaction_splits');
    }
}
