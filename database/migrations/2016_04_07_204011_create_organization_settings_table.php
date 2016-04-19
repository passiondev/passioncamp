<?php

// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;

// class CreateOrganizationSettingsTable extends Migration
// {
//     /**
//      * Run the migrations.
//      *
//      * @return void
//      */
//     public function up()
//     {
//         Schema::create('organization_settings', function (Blueprint $table) {
//             $table->increments('id');
//             $table->integer('organization_id')->unsigned()->index();
//             $table->string('key')->index();
//             $table->string('value');
//             $table->timestamps();
//         });
//     }

//     /**
//      * Reverse the migrations.
//      *
//      * @return void
//      */
//     public function down()
//     {
//         Schema::drop('organization_settings');
//     }
// }
