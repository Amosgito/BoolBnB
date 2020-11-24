<?php

use App\Apartment;
use App\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Message::class, 10)
            -> make()
            -> each(function($msg) {

            $apt = Apartment::inRandomOrder() -> first();
            $msg -> apartment() -> associate($apt);

            $msg -> save();
    
        });
    }
}
