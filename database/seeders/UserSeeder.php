<?php

namespace Database\Seeders;

use App\Enums\Position;
use App\Models\User;
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
                'name'            => 'English Cafe Super Admin',
                'email'           => 'officialenglishcafe@gmail.com',
                'password'        => bcrypt('englishcafealwaysdifferent'),
                'password_set_at' => now(),
                'position'        => Position::SuperAdmin->value,
            ],
            [
                'name'            => 'Muhammad Raditya Nur Aziz',
                'email' => 'radityadias24@gmail.com',
                'password'        => bcrypt('radityadias24'),
                'password_set_at' => now(),
                'position'        => Position::Internship->value,
            ]
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }

}
