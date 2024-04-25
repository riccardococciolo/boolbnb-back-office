<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
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
                'name' => 'Wi-Fi',
                'icon' => 'fa-solid fa-wifi'
            ],
            [
                'name' => 'Piscina',
                'icon' => 'fa-solid fa-umbrella-beach'
            ],
            [
                'name' => 'Servizio in Camera',
                'icon' => 'fa-solid fa-bell-concierge'
            ],
            [
                'name' => 'Area Fumatori',
                'icon' => 'fa-solid fa-smoking'
            ],
            [
                'name' => 'Aria Condizionata',
                'icon' => 'fa-solid fa-wind'
            ],
            [
                'name' => 'Posto Auto',
                'icon' => 'fa-solid fa-square-parking'
            ],
            [
                'name' => 'Animali Ammessi',
                'icon' => 'fa-solid fa-dog'
            ]
        ];

        foreach ($services as $service) {
            $new_service = new Service();

            $new_service->name = $service['name'];
            $new_service->icon = $service['icon'];

            $new_service->save();
        }
        
    }
}
