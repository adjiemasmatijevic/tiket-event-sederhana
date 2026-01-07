<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vouchers')->insert([
            [
                'voucher_code' => 'SPSCRTV',
                'type' => 'fixed',
                'value' => 10000,
                'limit' => 10,
                'expired_at' => '2026-01-12',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'voucher_code' => 'YHLZTGL',
                'type' => 'percentage',
                'value' => 10,
                'limit' => 10,
                'expired_at' => '2026-01-12',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
