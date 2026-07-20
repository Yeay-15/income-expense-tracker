<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Membuat akun demo Admin & User untuk keperluan pengujian/penilaian.
     * Email langsung ditandai verified supaya tidak terblokir middleware 'verified'.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin Demo',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_banned' => false,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@mail.com'],
            [
                'name' => 'User Demo',
                'password' => Hash::make('user12345'),
                'role' => 'user',
                'is_banned' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
