<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@roomia.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        // Host
        $host = User::firstOrCreate(
            ['email' => 'host@roomia.com'],
            [
                'name' => 'Host User',
                'password' => Hash::make('password'),
            ]
        );
        $host->assignRole('host');

        // Guest
        $guest = User::firstOrCreate(
            ['email' => 'guest@roomia.com'],
            [
                'name' => 'Guest User',
                'password' => Hash::make('password'),
            ]
        );
        $guest->assignRole('guest');
    }
}
