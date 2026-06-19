<?php

namespace database\seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\BranchesSeeder;
use Database\Seeders\DivisionsSeeder;
use Database\Seeders\LeaveTypesSeeder;
use Database\Seeders\WorkingTimesSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'raditya',
            'email' => 'raditya@gmail.com',
            'password' => bcrypt('radityadias'),
        ]);

        $this->call([
            BranchesSeeder::class,
            DivisionsSeeder::class,
            LeaveTypesSeeder::class,
            WorkingTimesSeeder::class,
        ]);
    }
}
