<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
                'password' => Hash::make('Admin@123'),
                'name' => 'admin',
                'phone' => '085933887666',
                'role' => 'admin',
            ]
        );
    }
}
