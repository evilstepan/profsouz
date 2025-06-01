<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Проверяем существование админа
        $admin = DB::table('users')
            ->where('email', 'sss@mail.ru')
            ->first();

        if ($admin) {
            // Обновляем пароль админа
            DB::table('users')
                ->where('email', 'sss@mail.ru')
                ->update([
                    'password' => Hash::make('admin123'), // Устанавливаем новый пароль
                    'role' => 'admin',
                    'status' => 'approved'
                ]);
        } else {
            // Создаем админа, если его нет
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'sss@mail.ru',
                'password' => Hash::make('admin123'), // Устанавливаем новый пароль
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