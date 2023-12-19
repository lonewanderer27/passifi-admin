<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendeesTable extends Migration
{
    public function up()
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('event_id')->references('id')->on('events');
            $table->foreignId('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendees');
    }
}
