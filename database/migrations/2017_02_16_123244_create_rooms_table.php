<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('organization_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('notes')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('roomnumber')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('hotel_id')->nullable();
            $table->string('confirmation_number')->nullable();

            $table->dateTime('key_received_at')->nullable();
            $table->dateTime('checked_in_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('rooms');
    }
}
