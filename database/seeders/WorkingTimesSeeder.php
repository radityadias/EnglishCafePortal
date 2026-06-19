<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkingTimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('working_times')->insert([
            [
                'name' => 'Shift Pagi',
                'from' => Carbon::createfromTime(8, 00, 00, 'Asia/Jakarta'),
                'to' => Carbon::createfromTime(17, 00, 00, 'Asia/Jakarta'),
            ],
            [
                'name' => 'Shift Siang',
                'from' => Carbon::createfromTime(11, 00, 00, 'Asia/Jakarta'),
                'to' => Carbon::createfromTime(20, 00, 00, 'Asia/Jakarta'),
            ]
        ]);
    }
}
