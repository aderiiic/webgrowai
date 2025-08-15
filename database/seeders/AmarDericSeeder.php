<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AmarDericSeeder extends Seeder
{
    public function run(): void
    {
        // Skapa/uppdatera användare Amar
        $amar = User::firstOrCreate(
            ['email' => 'amar@webbi.se'],
            [
                'name' => 'Amar Deric',
                'password' => Hash::make('CHANGE_ME_STRONG'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Skapa kund Webbi AB
        $customer = Customer::firstOrCreate(
            ['name' => 'Webbi AB'],
            [
                'contact_email' => 'amar@webbi.se',
                'status' => 'active',
            ]
        );

        // Koppla Amar till kunden
        if (method_exists($amar, 'customers')) {
            $amar->customers()->syncWithoutDetaching([$customer->id => ['role_in_customer' => 'owner']]);
        }

        // Skapa en sajt för kunden (om saknas)
        Site::firstOrCreate(
            ['customer_id' => $customer->id, 'url' => 'https://webbi.se'],
            ['name' => 'Webbi.se']
        );

        // Koppla admin (om finns) till kunden för enkel växling
        $admin = User::where('role', 'admin')->first();
        if ($admin && method_exists($admin, 'customers')) {
            $admin->customers()->syncWithoutDetaching([$customer->id => ['role_in_customer' => 'admin']]);
        }
    }
}
