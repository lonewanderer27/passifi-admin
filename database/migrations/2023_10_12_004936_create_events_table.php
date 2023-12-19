<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->longText('avatar')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->dateTime('date');
            $table->time('time');
            $table->string('location');
            $table->string("organizer");
            $table->string("organizer_email");
            $table->boolean('organizer_approval');
            $table->string("invite_code");
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}
