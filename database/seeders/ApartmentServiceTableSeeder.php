<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //selezioniamo tutti gli appartamenti in una variabile
        $apartments = Apartment::all();
        //cicliamo gli appartamenti
        foreach($apartments as $apartment){
            //creiamo un array dove salveremo i servizi da controllare e ciclare
            $service_random = [];
            //cicliamo in maniera da avere tre servizi
            for($i = 0; $i < 3 ; $i++){
                //prendo un servizio random lo salvo in una variabile
                $number = Service::inRandomOrder()->first()->id;
                //se questo servizio non è presente nell'array da ciclare lo pushamo
                    if(!in_array($number,$service_random)){
                        $service_random[] = $number;
                        //altrimenti peschiamo unaltro servizio e pushamo
                    }else{
                        $number = Service::inRandomOrder()->first()->id;
                        $service_random[] = $number;
                }
            }
            //una volta che abbiamo i tre servizi non uguali creiamo le relazioni con l'appartamento
            foreach($service_random as $service_id){
                $apartment->services()->attach($service_id);
            }
        }
    }
}
