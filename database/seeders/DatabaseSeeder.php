<?php

namespace database\seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\BranchSeeder;
use Database\Seeders\DivisionSeeder;
use Database\Seeders\LeaveTypeSeeder;
use Database\Seeders\WorkingTimeSeeder;
use Database\Seeders\UserSeeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BranchSeeder::class,
            DivisionSeeder::class,
            LeaveTypeSeeder::class,
            WorkingTimeSeeder::class,
            UserSeeder::class,
        ]);
    }
}
