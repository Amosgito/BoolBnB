<?php

use Illuminate\Database\Seeder;
use App\Visit;
use App\Apartment;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        factory(Visit::class, 10)
            -> make()
            -> each(function($vis) {

            $apt = Apartment::inRandomOrder() -> first();
            $vis -> apartment() -> associate($apt);

            $vis -> save();
    
        });
    }
}
