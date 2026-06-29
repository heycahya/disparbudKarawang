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
        User::create([
            'name' => 'Super Admin Disparbud',
            'email' => 'superadmin@disparbud.test',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Admin Disparbud',
            'email' => 'admin@disparbud.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Masyarakat 1',
            'email' => 'public1@example.com',
            'password' => Hash::make('password'),
            'role' => 'public',
        ]);

        User::create([
            'name' => 'Masyarakat 2',
            'email' => 'public2@example.com',
            'password' => Hash::make('password'),
            'role' => 'public',
        ]);
    }
}
