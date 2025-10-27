<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'admin',
                'guard_name' => 'web',
                'icon' => 'fas fa-user-shield',
                'label' => 'Administrator',
                'description' => 'User with full access rights',
            ],
            [
                'name' => 'guru',
                'guard_name' => 'web',
                'icon' => 'fas fa-chalkboard-teacher',
                'label' => 'Guru',
                'description' => 'User with teacher access rights',
            ],
            [
                'name' => 'siswa',
                'guard_name' => 'web',
                'icon' => 'fas fa-user-graduate',
                'label' => 'Siswa',
                'description' => 'User with student access rights',
            ],
            [
                'name' => 'parents',
                'guard_name' => 'web',
                'icon' => 'fas fa-user-friends',
                'label' => 'Orang Tua',
                'description' => 'User with parent access rights',
            ],
            [
                'name' => 'peserta-ppdb',
                'guard_name' => 'web',
                'icon' => 'fas fa-user-tie',
                'label' => 'Peserta PPDB',
                'description' => 'User with PPDB participant access rights',
            ],
        ];

        foreach ($data as $role) {
            \Spatie\Permission\Models\Role::create($role);
        }
    }
}
