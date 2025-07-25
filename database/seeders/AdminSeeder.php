<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@clinic.com',
            'password' => Hash::make('admin123'),
            'role_type' => 'admin',
        ]);

        $admin->assignRole('admin');
    }
}