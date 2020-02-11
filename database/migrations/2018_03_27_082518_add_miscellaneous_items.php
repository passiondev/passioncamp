<?php

use App\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMiscellaneousItems extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // optional(Item::where('type', 'deposit')->first())->update([
        //     'type' => 'other',
        //     'name' => 'Non-refundable deposit',
        // ]);

        // Item::create([
        //     'name' => 'Miscellaneous',
        //     'type' => 'other',
        // ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
        });
    }
}
