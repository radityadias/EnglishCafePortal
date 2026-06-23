<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('divisions')->insert([
            [
                'name' => 'IT / Engineering',
            ], [
                'name' => 'Human Resources'
            ], [
                'name' => 'Creative & Multimedia'
            ], [
                'name' => 'Marketing & Social Media'
            ], [
                'name' => 'Education / Language'
            ], [
                'name' => 'Administration'
            ], [
                'name' => 'Finance'
            ]
        ]);
    }
}
