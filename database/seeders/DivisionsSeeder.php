<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('divisions')->insert([
            [
                'name' => 'Graphic Design'
            ], [
                'name' => 'Human Resources Development (HRD)'
            ], [
                'name' => 'Key Opinion Leader (KOL)'
            ]
        ]);
    }
}
