<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestsTable extends Migration
{
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('event_id')->references('id')->on('events');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->boolean('approved')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('guests');
    }
}
