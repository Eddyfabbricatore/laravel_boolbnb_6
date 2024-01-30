<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Sponsor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class ApartmentSponsorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for($i = 0; $i < 750 ;$i++){

            $apartment = Apartment::inRandomOrder()->first();

            $sponsor = Sponsor::inRandomOrder()->first();

            $transactionDate = $faker->dateTimeBetween('-2 years', 'yesterday');

            $endSponsorDate = $faker->dateTimeBetween('-2 years', 'yesterday');

            $apartment->sponsors()->attach($sponsor, [
                'transaction_date' => $transactionDate,
                'end_sponsor_date' => $endSponsorDate,
            ]);

        }
    }
}

