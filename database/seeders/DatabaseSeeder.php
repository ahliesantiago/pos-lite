<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProductType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'isAdmin' => true,
        ]);

        ProductType::create([
            'type_name' => 'Others',
        ]);
    }
}
