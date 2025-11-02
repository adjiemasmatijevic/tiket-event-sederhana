<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->uuid('ots_id')
                ->nullable()
                ->default(null)
                ->after('user_id');

            $table->foreign('ots_id')
                ->references('id')
                ->on('ots')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['ots_id']);
            $table->dropColumn('ots_id');
        });
    }
};
