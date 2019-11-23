<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->nullable()->unique();
            $table->unsignedInteger('church_id')->nullable()->index();
            $table->unsignedInteger('contact_id')->nullable()->index();
            $table->unsignedInteger('student_pastor_id')->nullable()->index();
            $table->string('contact_desciption')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('organizations');
    }
}
