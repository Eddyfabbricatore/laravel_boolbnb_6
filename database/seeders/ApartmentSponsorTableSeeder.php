<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Sponsor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentSponsorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0; $i < 24 ;$i++){

            $apartment = Apartment::inRandomOrder()->first();

            $sponsor = Sponsor::inRandomOrder()->first();

            $apartment->sponsors()->attach($sponsor->id);

        }
    }
}

