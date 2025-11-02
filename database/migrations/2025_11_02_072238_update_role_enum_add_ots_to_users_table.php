<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Ubah enum role agar mendukung nilai 'ots'
        DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'checker', 'admin', 'ots') DEFAULT 'user'");
    }

    public function down(): void
    {
        // Kembalikan enum ke semula (tanpa 'ots')
        DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'checker', 'admin') DEFAULT 'user'");
    }
};
