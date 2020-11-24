<?php

use App\Apartment;
use App\Promotion;
use App\Sponsorship;
use Illuminate\Database\Seeder;

class SponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        factory(Sponsorship::class, 50) -> make()
                                                -> each(function($spo){
                                                    $apt = Apartment::inRandomOrder() -> first();
                                                    $pro = Promotion::inRandomOrder() -> first();

                                                    $spo -> apartment() -> associate($apt);
                                                    $spo -> promotion() -> associate($pro);

                                                    $spo -> save();
                                                    
                                                });

      
    }
}
