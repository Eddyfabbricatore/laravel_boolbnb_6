<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SponsorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sponsors = config('apartments_sponsors');

        foreach($sponsors as $sponsor){
            DB::table('sponsors')->insert($sponsor);
        }
    }

}
