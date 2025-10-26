<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::firstOrCreate([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '0123456789',
            'username' => 'admin',
            'code' => uniqid(),
            'password' => Hash::make('password'),
        ]);

        // Assign roles to users
        $user->syncRoles(['admin', 'guru', 'siswa', 'parents']);
    }
}
