<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'nomor_induk' => 'ADM001',
            ]
        );

        // Akun Dosen
        User::updateOrCreate(
            ['email' => 'dosen@gmail.com'],
            [
                'name' => 'Andhika Rama',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '1987654321',
            ]
        );

        // Akun Mahasiswa
        User::updateOrCreate(
            ['email' => 'mahasiswa@gmail.com'],
            [
                'name' => 'Mahasiswa LMS',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '102042500035',
            ]
        );
    }
}
