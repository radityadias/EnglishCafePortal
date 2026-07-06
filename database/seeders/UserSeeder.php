<?php

namespace Database\Seeders;

use App\Models\EmployeeProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'English Cafe Admin',
            'email' => 'admin.englishcafe@gmail.com',
            'password' => bcrypt('englishcafealwaysdifferent'),
            'password_set_at' => now(),
            'position' => 'Super Admin',
        ]);

        EmployeeProfile::firstOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'work_time_start' => Carbon::createFromTime(8, 00, 00),
                'branch_id' => 1
            ]
        );
    }

}
