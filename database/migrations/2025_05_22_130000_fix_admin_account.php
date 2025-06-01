<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Сначала восстанавливаем колонку password
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password')->after('email');
            }
        });

        // Затем обновляем или создаем админа
        $user = DB::table('users')->where('email', 'sss@mail.ru')->first();
        
        if ($user) {
            // Обновляем существующего пользователя
            DB::table('users')
                ->where('email', 'sss@mail.ru')
                ->update([
                    'password' => Hash::make('admin'),
                    'role' => 'admin',
                    'status' => 'approved'
                ]);
        } else {
            // Создаем нового админа
            DB::table('users')->insert([
                'last_name' => 'Admin',
                'first_name' => 'User',
                'middle_name' => null,
                'email' => 'sss@mail.ru',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function down()
    {
        // В down() можно оставить пустым, так как это необратимая операция
    }
}; 