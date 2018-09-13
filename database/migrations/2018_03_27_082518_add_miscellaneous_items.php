<?php

use App\Item;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMiscellaneousItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
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
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
}
