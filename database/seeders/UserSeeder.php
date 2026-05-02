<?php

namespace Database\Seeders;

use App\Enums\RoleEnums;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'super admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role' => RoleEnums::Super_Admin->value,
            ],

            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => RoleEnums::Admin->value,
            ],
            [
                'name' => 'user',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => RoleEnums::User->value
            ]
        ];

        foreach($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']], [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                ]);
            $user ->assignRole($data['role']);
        }
    }
}
