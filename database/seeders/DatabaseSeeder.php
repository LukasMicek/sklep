<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Users (role: seller) ---
        User::updateOrCreate(
            ['email' => 'seller@demo.pl'],
            [
                'name' => 'Sprzedawca Demo',
                'password' => Hash::make('password'),
                'role' => 'seller',
            ]
        );

        User::updateOrCreate(
            ['email' => 'client@demo.pl'],
            [
                'name' => 'Klient Demo',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        // --- Categories ---
        $catNames = ['Rum', 'Whisky', 'Gin', 'Wódka', 'Tequila'];
        $categories = [];

        foreach ($catNames as $name) {
            $slug = Str::slug($name);

            $categories[$name] = Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
        }

        // --- Products ---
        $products = [
            ['name' => 'Havana Club 3', 'cat' => 'Rum', 'price' => 6999, 'stock' => 25],
            ['name' => 'Bacardi Carta Blanca', 'cat' => 'Rum', 'price' => 7499, 'stock' => 15],
            ['name' => 'Jameson', 'cat' => 'Whisky', 'price' => 8999, 'stock' => 20],
            ['name' => 'Jack Daniel’s', 'cat' => 'Whisky', 'price' => 10999, 'stock' => 10],
            ['name' => 'Beefeater', 'cat' => 'Gin', 'price' => 8499, 'stock' => 18],
            ['name' => 'Bombay Sapphire', 'cat' => 'Gin', 'price' => 9999, 'stock' => 12],
            ['name' => 'Finlandia', 'cat' => 'Wódka', 'price' => 5499, 'stock' => 30],
            ['name' => 'Absolut', 'cat' => 'Wódka', 'price' => 6799, 'stock' => 22],
            ['name' => 'Olmeca Blanco', 'cat' => 'Tequila', 'price' => 8999, 'stock' => 14],
            ['name' => 'Jose Cuervo', 'cat' => 'Tequila', 'price' => 7999, 'stock' => 16],
        ];

        foreach ($products as $p) {
            $slug = Str::slug($p['name']);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $p['name'],
                    'slug' => $slug,
                    'category_id' => $categories[$p['cat']]->id,
                    'description' => 'Opis testowy produktu: ' . $p['name'],
                    'price_cents' => $p['price'],
                    'stock' => $p['stock'],
                    'is_active' => true,
                ]
            );
        }

        // --- Coupons ---
        Coupon::updateOrCreate(
            ['code' => 'PROMO10'],
            [
                'type' => 'percent',
                'value' => 10,
                'active' => true,
                'expires_at' => null,
                'min_order_cents' => null,
                'max_uses' => null,
                'used_count' => 0,
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'MINUS5'],
            [
                'type' => 'fixed',
                'value' => 500,
                'active' => true,
                'expires_at' => null,
                'min_order_cents' => 3000,
                'max_uses' => null,
                'used_count' => 0,
            ]
        );
    }
}