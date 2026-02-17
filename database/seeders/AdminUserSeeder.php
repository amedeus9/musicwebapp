<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminExists = User::where('is_admin', true)->exists();

        if ($adminExists) {
            $this->command->info('Admin user already exists!');
            return;
        }

        // Create default admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@musicapp.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@musicapp.com');
        $this->command->info('Password: admin123');
        $this->command->warn('Please change the password after first login!');
    }
}
