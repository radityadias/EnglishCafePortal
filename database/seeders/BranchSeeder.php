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
                'name' => 'Kantor English Cafe Kotagede',
                'latitude' => -7.8161472,
                'longitude' => 110.3935871
            ],
            [
                'name' => 'English Cafe UNS',
                'latitude' => null,
                'longitude' => null
            ],
            [
                'name' => 'English Cafe USM',
                'latitude' => null,
                'longitude' => null
            ],
            [
                'name' => 'English Cafe UNDIP',
                'latitude' => null,
                'longitude' => null
            ],
            [
                'name' => 'English Cafe UMY',
                'latitude' => null,
                'longitude' => null
            ],
            [
                'name' => 'English Cafe Alive Fusion Dining',
                'latitude' => null,
                'longitude' => null
            ],
            [
                'name' => 'English Cafe Depok UI',
                'latitude' => null,
                'longitude' => null
            ],
            [
                'name' => 'English Cafe UB',
                'latitude' => null,
                'longitude' => null
            ]
        ]);
    }
}

