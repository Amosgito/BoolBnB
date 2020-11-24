<?php

use Illuminate\Database\Seeder;
use App\Promotion;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $proData = [
            [
                'price' => '2.99',
                'hours' => '24',
            ],
            [
                'price' => '5.99',
                'hours' => '48',
            ],
            [
                'price' => '9.99',
                'hours' => '144',
            ]
        ];

        foreach ($proData as $pro) {
            
            Promotion::create($pro);
                     
        }
    }
}
