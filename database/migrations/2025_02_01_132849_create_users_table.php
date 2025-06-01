<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateUsersTable extends Migration
{
    public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('last_name');
        $table->string('first_name');
        $table->string('middle_name')->nullable();
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('phone')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->string('position')->nullable(); // Должность/специальность
        $table->string('passport_series')->nullable();
        $table->string('passport_number')->nullable();
        $table->date('passport_issue_date')->nullable();
        $table->string('passport_issued_by')->nullable();
        $table->boolean('personal_data_agreement')->default(false);
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->text('rejection_reason')->nullable();
        $table->string('membership_number')->nullable()->unique();
        $table->timestamp('approved_at')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('users');
    }
}




