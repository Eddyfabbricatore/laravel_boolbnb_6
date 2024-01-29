<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\View;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class ViewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {


        for($i = 0; $i < 1500 ; $i++){

            $newView = new View();
            $newView->apartment_id = Apartment::inRandomOrder()->first()->id;
            $newView->ip = $faker->ipv4();
            $newView->view_date = $faker->dateTimeBetween('-4 years', 'yesterday');

            $newView->save();
        }
    }

}
