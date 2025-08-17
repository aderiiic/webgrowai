<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'amar.deric@webbi.se'],
            ['name' => 'Admin', 'password' => Hash::make('M3ilPh0n3t!?'), 'role' => 'admin']
        );
    }
}
