<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'English Cafe Admin',
            'email' => 'admin.englishcafe@gmail.com',
            'password' => bcrypt('englishcafealwaysdifferent'),
            'position' => 'Super Admin',
        ]);
    }

}
