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
                'latitude' => -7.54861616,
                'longitude' => 110.8588231
            ],
            [
                'name' => 'English Cafe USM',
                'latitude' => -6.9777491,
                'longitude' => 110.4492854
            ],
            [
                'name' => 'English Cafe UNDIP',
                'latitude' => -7.0644534,
                'longitude' => 110.4282243
            ],
            [
                'name' => 'English Cafe UMY',
                'latitude' => -7.8144675,
                'longitude' => 110.3234647
            ],
            [
                'name' => 'English Cafe Alive Fusion Dining',
                'latitude' => -7.7963291,
                'longitude' => 110.3924619,
            ],
            [
                'name' => 'English Cafe Depok UI',
                'latitude' => -6.3755413,
                'longitude' => 106.8284594
            ],
            [
                'name' => 'English Cafe UB',
                'latitude' => -7.9434482,
                'longitude' => 112.6182829
            ],
            [
                'name' => 'English Cafe UGM',
                'latitude' => -7.7619047,
                'longitude' => 110.3753233
            ],
            [
                'name' => 'English Cafe Malang',
                'latitude' => -7.9268660,
                'longitude' => 112.6264369
            ],
            [
                'name' => 'English Cafe UII',
                'latitude' => -7.6933495,
                'longitude' => 110.4132909
            ],
            [
                'name' => 'English Cafe UIN Malang',
                'latitude' => -7.9525785,
                'longitude' => 112.6032842
            ],
            [
                'name' => 'English Cafe ITB',
                'latitude' => -6.8946121,
                'longitude' => 107.6148980
            ],
            [
                'name' => 'English Cafe Telkom',
                'latitude' => -6.9722746,
                'longitude' => 107.6513857
            ],
            [
                'name' => 'English Cafe UPN Jogja',
                'latitude' => -7.7754535,
                'longitude' => 110.4118225
            ],
            [
                'name' => 'English Cafe UNPAD',
                'latitude' => -6.9339701,
                'longitude' => 107.7735981
            ],
            [
                'name' => 'English Cafe UM',
                'latitude' => -7.9582441,
                'longitude' => 112.6345144
            ],
            [
                'name' => 'English Cafe Nologaten',
                'latitude' => -7.7785577,
                'longitude' => 110.3997925
            ],
            [
                'name' => 'English Cafe UMS',
                'latitude' => -7.5532153,
                'longitude' => 110.7625264
            ],
            [
                'name' => 'English Cafe Udinus',
                'latitude' => -6.9823686,
                'longitude' => 110.4177324
            ],
            [
                'name' => 'English Cafe Tembalang',
                'latitude' => -7.0634042,
                'longitude' => 110.4368004
            ],
            [
                'name' => 'English Cafe UMM',
                'latitude' => -7.9243113,
                'longitude' => 112.5845003
            ],
            [
                'name' => 'English Cafe Indraloka',
                'latitude' => -7.7796018,
                'longitude' => 110.3757923
            ],
            [
                'name' => 'English Cafe Bandung',
                'latitude' => -6.9264918,
                'longitude' => 107.6240969
            ],
            [
                'name' => 'English Cafe Padang',
                'latitude' => -0.8818473,
                'longitude' => 100.3614271
            ],

        ]);
    }
}

