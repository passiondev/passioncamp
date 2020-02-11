<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditCountsOnRoomingListsVersionsTable extends Migration
{
    /**
     * Run the migrations.
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
     */
    public function down()
    {
        Schema::table('rooming_list_versions', function (Blueprint $table) {
        });
    }
}
