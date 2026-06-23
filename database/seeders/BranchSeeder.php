<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('branches')->insert([
            [
                'name' => 'Kantor English Cafe Kotagede'
            ],
            [
                'name' => 'English Cafe UNS'
            ],
            [
                'name' => 'English Cafe USM'
            ],
            [
                'name' => 'English Cafe UNDIP'
            ],
            [
                'name' => 'English Cafe UMY'
            ],
            [
                'name' => 'English Cafe Alive Fusion Dining'
            ],
            [
                'name' => 'English Cafe Depok UI'
            ],
            [
                'name' => 'English Cafe UB'
            ],
            [
                'name' => 'Hybrid'
            ]
        ]);
    }
}

