<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCanceledAtToWaiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waivers', function (Blueprint $table) {
            $table->timestamp('canceled_at')->nullable();
            $table->integer('canceled_by_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waivers', function (Blueprint $table) {
            //
        });
    }
}
