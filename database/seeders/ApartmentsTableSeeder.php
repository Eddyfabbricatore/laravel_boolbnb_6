<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Functions\Helper;
use App\Models\Apartment;

class ApartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apartments = config('apartments');

        foreach($apartments as $apartment){
            $apartment['slug'] = Helper::generateSlug($apartment['title'], Apartment::class);
            DB::table('apartments')->insert($apartment);
        }
    }
}
