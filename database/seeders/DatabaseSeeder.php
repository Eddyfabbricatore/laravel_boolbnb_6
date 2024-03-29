<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            ServicesTableSeeder::class,
            ApartmentsTableSeeder::class,
            ViewsTableSeeder::class,
            MessagesTableSeeder::class,
            SponsorTableSeeder::class,
            ApartmentServiceTableSeeder::class,
            ApartmentSponsorTableSeeder::class,
        ]);
    }
}
