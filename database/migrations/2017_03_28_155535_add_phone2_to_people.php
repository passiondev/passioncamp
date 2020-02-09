<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhone2ToPeople extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->string('phone2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
        });
    }
}
