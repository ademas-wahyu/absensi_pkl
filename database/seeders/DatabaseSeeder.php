<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        $murid = User::firstOrCreate(
            ['email' => 'murid@example.com'],
            [
                'name' => 'Murid User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
        $murid->assignRole('murid');
    }
}
