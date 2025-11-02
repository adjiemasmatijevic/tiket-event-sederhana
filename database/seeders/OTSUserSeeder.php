<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OTSUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'ots@gmail.com',
                'password' => Hash::make('$Rp#o8v}2U-Z-ew.4n9pSBHj1E_2+tcj'),
                'name' => 'ots',
                'gender' => 'male',
                'phone' => '08XXXXXXXX',
                'address' => 'ots Address',
                'role' => 'ots',
            ]
        );
    }
}
