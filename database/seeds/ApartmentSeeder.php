<?php

use App\User;
use App\Apartment;
use App\Service;
use App\Sponsorship;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        factory(Apartment::class, 10)
            -> make()
            -> each(function($apt) {
            
                $usr = User::inRandomOrder() -> first();
                $apt -> user() -> associate($usr);      

                $apt -> save();
                             
        });


    }
}
