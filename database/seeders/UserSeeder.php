<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'role' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => 'password',
            ],
            [
                'name' => 'Sales',
                'role' => 'sales',
                'email' => 'sales@gmail.com',
                'password' => 'password',
            ],
            [
                'name' => 'Control',
                'role' => 'control',
                'email' => 'control@gmail.com',
                'password' => 'password',
            ],
            [
                'name' => 'Support',
                'role' => 'support',
                'email' => 'support@gmail.com',
                'password' => 'password',
            ],

        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
