<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuestIdToAttendeesTable extends Migration
{
    public function up()
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->foreignId('guest_id')->references('id')->on('guests');
        });
    }

    public function down()
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->dropColumn('guest_id');
        });
    }
}
