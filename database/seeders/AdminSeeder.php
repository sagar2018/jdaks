<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $existingAdmin = User::where('email', 'admin@jdaksinfra.com')->first();
        if ($existingAdmin) {
            $this->command->info('Admin user already exists. Skipping.');
            return;
        }

        $password = Str::random(12);

        $admin = User::create([
            'name'     => 'JDAKS Admin',
            'email'    => 'admin@jdaksinfra.com',
            'password' => Hash::make($password),
            'status'   => 'active',
        ]);

        $admin->assignRole('admin');

        $this->command->alert("Admin created! Email: admin@jdaksinfra.com | Password: {$password}");
        $this->command->warn('Change this password immediately after first login!');
    }
}