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
                'name' => 'General Affairs',
            ], [
                'name' => 'Chef Operational'
            ], [
                'name' => 'Master Chef'
            ], [
                'name' => 'Admin Sales'
            ], [
                'name' => 'Marketing & Sales'
            ], [
                'name' => 'Customer Care'
            ], [
                'name' => 'HRD'
            ],[
                'name' => 'Graphic Design'
            ]
        ]);
    }
}
