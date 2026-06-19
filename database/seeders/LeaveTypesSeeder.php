<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leave_types')->insert([
            [
                'name' => 'Sakit',
                'allowed_days' => 3
            ],
            [
                'name' => 'Cuti',
                'allowed_days' => 5
            ]
        ]);
    }
}
