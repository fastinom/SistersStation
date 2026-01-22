<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@babywear.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'user_type' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        echo "Admin user created successfully!\n";
        echo "Email: admin@babywear.com\n";
        echo "Password: admin123\n";
    }
}
