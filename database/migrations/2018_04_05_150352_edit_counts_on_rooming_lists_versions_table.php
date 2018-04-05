<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditCountsOnRoomingListsVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooming_list_versions', function (Blueprint $table) {
            $table->integer('revised_rooms')->nullable()->change();
            $table->integer('revised_tickets')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooming_list_versions', function (Blueprint $table) {
            //
        });
    }
}
