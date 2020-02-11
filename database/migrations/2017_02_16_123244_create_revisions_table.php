<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action');
            $table->string('table_name');
            $table->integer('row_id')->unsigned();
            $table->binary('old')->nullable();
            $table->binary('new')->nullable();
            $table->string('user')->nullable();
            $table->string('ip')->nullable();
            $table->string('ip_forwarded')->nullable();
            $table->timestamp('created_at');

            $table->index('action');
            $table->index(['table_name', 'row_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('revisions');
    }
}
