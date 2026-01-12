<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SellerUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'seller@sklep'],
            [
                'name' => 'Sprzedawca',
                'password' => Hash::make('Haslo123!'),
                'role' => 'seller',
            ]
        );
    }
}

