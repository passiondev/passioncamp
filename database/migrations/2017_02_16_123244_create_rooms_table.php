<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoomsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function(Blueprint $table)
        {
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
            $table->string('confirmation_number');
            $table->dateTime('key_received_at')->nullable();
            $table->dateTime('checked_in_at')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rooms');
    }

}
