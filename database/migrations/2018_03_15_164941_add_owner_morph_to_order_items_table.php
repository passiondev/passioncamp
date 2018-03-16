<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\OrderItem;

class AddOwnerMorphToOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string("owner_type")->nullable();
            $table->unsignedBigInteger("owner_id")->nullable();

            $table->index(["owner_type", "owner_id"]);

        });

        // where organization id is not null
        // set type = App\Organization and id to organization_id
        OrderItem::whereNotNull('organization_id')->withTrashed()->each(function ($item) {
            $item->timestamps = false;
            $item->update([
                'owner_type' => 'App\Organization',
                'owner_id'   => $item->organization_id,
            ]);
        });

        // where order id is not null
        // set type = App\Order and id to order_id
        OrderItem::whereNotNull('order_id')->withTrashed()->each(function ($item) {
            $item->timestamps = false;
            $item->update([
                'owner_type' => 'App\Order',
                'owner_id'   => $item->order_id,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            //
        });
    }
}
