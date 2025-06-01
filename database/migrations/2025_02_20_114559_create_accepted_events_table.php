<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcceptedEventsTable extends Migration
{
    public function up()
    {
        Schema::create('accepted_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Связь с пользователем
            $table->foreignId('event_id')->constrained()->onDelete('cascade'); // Связь с мероприятием
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accepted_events');
    }
}
