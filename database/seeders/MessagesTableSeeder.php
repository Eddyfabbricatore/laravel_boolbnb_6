<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {

        for($i = 0; $i < 500 ; $i++) {

            $newMessage = new Message();
            $newMessage->apartment_id = Apartment::inRandomOrder()->first()->id;
            $newMessage->full_name = $faker->name();
            $newMessage->email = $faker->email();
            $newMessage->message = $faker->sentence();
            $newMessage->date = $faker->dateTimeBetween('-2 years', 'yesterday');

            $newMessage->save();
        }
    }

}
