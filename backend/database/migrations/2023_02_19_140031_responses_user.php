<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responses_user', function (Blueprint $table) {
            $table->id();
            $table->string('favorite_food');
            $table->string('favorite_artist');
            $table->string('favorite_place');
            $table->string('favorite_color');
            $table->string('desc_ask_one');
            $table->string('desc_ask_two');
            $table->string('desc_ask_three');
            $table->string('desc_ask_four');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
