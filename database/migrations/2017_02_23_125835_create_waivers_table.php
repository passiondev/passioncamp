<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaiversTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('waivers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ticket_id');
            $table->string('provider', 20)->nullable();
            $table->string('provider_agreement_id')->nullable();
            $table->string('status', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('waivers');
    }
}
