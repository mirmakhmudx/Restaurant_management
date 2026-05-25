<?php

namespace Database\Seeders;

use App\Enums\StaffRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            ['name' => 'Sarah Johnson', 'email' => 'manager@biteplate.com', 'role' => StaffRole::Manager],
            ['name' => 'Tom Wilson',    'email' => 'waiter@biteplate.com',  'role' => StaffRole::Waiter],
            ['name' => 'Marco Ferrari', 'email' => 'chef@biteplate.com',    'role' => StaffRole::Chef],
            ['name' => 'Emma Davis',    'email' => 'cashier@biteplate.com', 'role' => StaffRole::Cashier],
        ];

        foreach ($staff as $member) {
            User::create([
                'name'      => $member['name'],
                'email'     => $member['email'],
                'password'  => Hash::make('password'),
                'role'      => $member['role'],
                'is_active' => true,
            ]);
        }
    }
}
