<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('instances')->insert([
            [
                'name' => 'English Cafe'
            ],
            [
                'name' => 'Imersa Solusi Teknologi'
            ],
            [
                'name' => 'Villa Kamar Tamu'
            ],
        ]);
    }
}
