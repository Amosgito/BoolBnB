<?php

use App\Apartment;
use App\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $services = [
            [
                'name' => 'Wi-fi',
                'icon' => "fas fa-wifi"
            ],[
                'name' => 'Swimming pool',
                'icon' => 'fas fa-swimmer'
            ],[
                'name' => 'Free Parking',
                'icon' => 'fas fa-parking'
            ],[
                'name' => 'SPA',
                'icon' => 'fas fa-spa'
            ],[
                'name' => 'H24 Reception',
                'icon' => 'fas fa-concierge-bell'
            ]    
                
        ];

        foreach ($services as $srv) {

            
            $newRecord = Service::create($srv);
            
            //$apt = Apartment::inRandomOrder() -> take(rand(0,5)) -> get();

            //$newRecord -> apartments() -> attach($apt);
        }
        
    }
}
