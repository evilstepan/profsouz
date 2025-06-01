<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; // Не забудьте добавить этот импорт

class AddUserIdToEventsTable extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            // Проверка на существование столбца перед его добавлением
            if (!Schema::hasColumn('events', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Добавление каскадного удаления
            }
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'user_id')) { // Проверка на существование столбца перед его удалением
                $table->dropForeign(['user_id']);
                $table->dropColumn(['user_id']);
            }
        });
    }
}