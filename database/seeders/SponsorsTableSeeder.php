<?php

namespace Database\Seeders;
use App\Models\Sponsor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SponsorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sponsor_plans = [
            [
                'tier' => 'Tier 1',
                'price' => 2.99,
                'duration' => 24,
            ],
            [
                'tier' => 'Tier 2',
                'price' => 5.99,
                'duration' => 72,
            ],
            [
                'tier' => 'Tier 3',
                'price' => 9.99,
                'duration' => 144,
            ]
        ];

        foreach($sponsor_plans as $sponsor_plan){
            $new_sponsor = new Sponsor();
            $new_sponsor->tier = $sponsor_plan['tier'];
            $new_sponsor->price = $sponsor_plan['price'];
            $new_sponsor->duration = $sponsor_plan['duration'];
            $new_sponsor->save();

        }
    }
}
