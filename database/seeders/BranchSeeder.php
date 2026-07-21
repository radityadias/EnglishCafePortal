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
                'latitude' => -7.5486290,
                'longitude' => 110.8588229
            ],
            [
                'name' => 'English Cafe USM',
                'latitude' => -6.9777567,
                'longitude' => 110.4492831
            ],
            [
                'name' => 'English Cafe UNDIP',
                'latitude' => -7.0644674,
                'longitude' => 110.4282272
            ],
            [
                'name' => 'English Cafe UMY',
                'latitude' => -7.8144847,
                'longitude' => 110.3234636
            ],
            [
                'name' => 'English Cafe Alive Fusion Dining',
                'latitude' => -7.7964360,
                'longitude' => 110.3924707
            ],
            [
                'name' => 'English Cafe Depok UI',
                'latitude' => -6.3755547,
                'longitude' => 106.8284575
            ],
            [
                'name' => 'English Cafe UB',
                'latitude' => -7.9434821,
                'longitude' => 112.6182900
            ],
            [
                'name' => 'English Cafe UGM',
                'latitude' => -7.7619182,
                'longitude' => 110.3753239
            ],
            [
                'name' => 'English Cafe Malang',
                'latitude' => -7.9268753,
                'longitude' => 112.6264359
            ],
            [
                'name' => 'English Cafe UII',
                'latitude' => -7.6933700,
                'longitude' => 110.4132893
            ],
            [
                'name' => 'English Cafe UIN Malang',
                'latitude' => -7.9525763,
                'longitude' => 112.6032872
            ],
            [
                'name' => 'English Cafe ITB',
                'latitude' => -6.8946351,
                'longitude' => 107.6149021
            ],
            [
                'name' => 'English Cafe Telkom',
                'latitude' => -6.9722840,
                'longitude' => 107.6513900
            ],
            [
                'name' => 'English Cafe UPN Jogja',
                'latitude' => -7.7754542,
                'longitude' => 110.4118218
            ],
            [
                'name' => 'English Cafe UNPAD',
                'latitude' => -6.9339717,
                'longitude' => 107.7735954
            ],
            [
                'name' => 'English Cafe UM',
                'latitude' => -7.9582415,
                'longitude' => 112.6345142
            ],
            [
                'name' => 'English Cafe Nologaten',
                'latitude' => -7.7785552,
                'longitude' => 110.3997929
            ],
            [
                'name' => 'English Cafe UMS',
                'latitude' => -7.55321850,
                'longitude' => 110.7625290
            ],
            [
                'name' => 'English Cafe Udinus',
                'latitude' => -6.9824639,
                'longitude' => 110.4175184
            ],
            [
                'name' => 'English Cafe Tembalang',
                'latitude' => -7.0634018,
                'longitude' => 110.4368007
            ],
            [
                'name' => 'English Cafe UMM',
                'latitude' => -7.9243125,
                'longitude' => 112.5845012
            ],
            [
                'name' => 'English Cafe Indraloka',
                'latitude' => -7.7796011,
                'longitude' => 110.3757941
            ],
            [
                'name' => 'English Cafe Bandung',
                'latitude' => -6.9264669,
                'longitude' => 107.6240910
            ],
            [
                'name' => 'English Cafe Padang',
                'latitude' => -0.8818464,
                'longitude' => 100.3614269
            ],

        ]);
    }
}

