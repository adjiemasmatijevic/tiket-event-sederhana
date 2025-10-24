<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('image', 32)->unique();
            $table->string('name', 64)->unique();
            $table->string('slug', 128)->unique();
            $table->text('description');
            $table->dateTime('time_start');
            $table->dateTime('time_end');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
