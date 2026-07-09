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
                'email'           => 'admin.englishcafe@gmail.com',
                'password'        => bcrypt('englishcafealwaysdifferent'),
                'password_set_at' => now(),
                'position'        => Position::SuperAdmin->value,
            ],
            [
                'name'            => 'English Cafe HRD',
                'email'           => 'hrd.englishcafe@gmail.com',
                'password'        => bcrypt('hrdenglishcafe'),
                'password_set_at' => now(),
                'position'        => Position::Admin->value,
            ],
            [
                'name'            => 'El',
                'email'           => 'el@gmail.com',
                'password'        => bcrypt('elenglishcafe'),
                'password_set_at' => now(),
                'position'        => Position::Employee->value,
            ],
            [
                'name'            => 'Radit',
                'email'           => 'radit@gmail.com',
                'password'        => bcrypt('raditenglishcafe'),
                'password_set_at' => now(),
                'position'        => Position::Internship->value,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }

}
