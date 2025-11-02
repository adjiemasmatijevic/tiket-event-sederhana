<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom 'net'
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('net')->nullable()->after('amount_total');
        });

        // Isi nilai awal kolom 'net' untuk data yang sudah ada
        DB::table('transactions')->orderBy('id')->chunkById(100, function ($transactions) {
            foreach ($transactions as $transaction) {
                $net = $transaction->amount_total;

                if ($transaction->category === 'payment gateway') {
                    // Hitung harga sebelum admin 5%
                    $net = round($transaction->amount_total / 1.05);
                }

                DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->update(['net' => $net]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('net');
        });
    }
};
