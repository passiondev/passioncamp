<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dummy_digit')->nullable();

            $table->unsignedInteger('organization_id')->nullable()->index();
            $table->unsignedInteger('order_id')->nullable()->index();
            $table->unsignedInteger('item_id')->nullable()->index();

            $table->string('org_type')->nullable();
            $table->string('type')->nullable()->index();

            $table->integer('cost')->nullable();
            $table->integer('price')->nullable();
            $table->integer('quantity')->nullable()->default(1);

            $table->unsignedInteger('person_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('agegroup')->nullable();
            $table->string('squad')->nullable();
            $table->text('ticket_data')->nullable();

            $table->integer('cancel_fee')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->unsignedInteger('canceled_by_id')->nullable()->index();

            $table->dateTime('checked_in_at')->nullable();
            $table->unsignedInteger('checked_in_by_id')->nullable();

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
        Schema::drop('order_items');
    }
}
