<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create editor users
        for ($i = 1; $i <= 3; $i++) {
            User::updateOrCreate(
                ['email' => "editor{$i}@example.com"],
                [
                    'name' => "Editor User {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'editor',
                ]
            );
        }

        // Create regular users
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name' => "Regular User {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'user',
                ]
            );
        }
    }
}
