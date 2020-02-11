<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('room_assignments', function (Blueprint $table) {
            $table->index('ticket_id');
        });
        Schema::table('waivers', function (Blueprint $table) {
            $table->index(['status', 'ticket_id']);
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['type', 'canceled_at', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
