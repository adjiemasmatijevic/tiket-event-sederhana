<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email', 255)->unique();
            $table->char('password', 60);
            $table->string('name', 64)->unique();
            $table->enum('gender', ['male', 'female']);
            $table->string('phone', 20)->unique();
            $table->text('address');
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->string('profile_picture', 32)->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
