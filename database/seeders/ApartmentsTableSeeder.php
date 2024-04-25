<?php

namespace Database\Seeders;

use App\Models\Apartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use GuzzleHttp\Client;

class ApartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $address        = ['Milano', 'Torino', 'Roma', 'Londra', 'New York', 'Tokyo'];
        $apartmentNames = ['Luxury House', 'Real Luxury House', 'Simple House', 'Big House', 'Small House', 'Chalet', 'Bay', 'Sea House', 'Beautyfull House', 'Hotel'];

        
        for ($i=0; $i < 10; $i++) { 
            $apartment = new Apartment();
            $apartment->user_id = $faker->numberBetween(1, 5);
            $apartment->title = $apartmentNames[$i];
            $apartment->slug = Str::slug($apartment->title);
            $apartment->address = $faker->randomElement($address);
            $lat_lon = $this->getCoordinatesFromAddress($apartment->address);
            $apartment->latitude = $lat_lon['coordinates']['lat'];
            $apartment->longitude = $lat_lon['coordinates']['lon'];
            $apartment->price = $faker->randomFloat(2, 1, 1999);
            $apartment->dimension_mq = $faker->numberBetween(0, 500);
            $apartment->rooms_number = $faker->numberBetween(2, 20);
            $apartment->beds_number = $faker->numberBetween(1, 10);
            $apartment->bathrooms_number = $faker->numberBetween(1, 10);
            $apartment->is_visible = $faker->numberBetween(0, 1);

            $apartment->save();
        }
    }
    public static function getCoordinatesFromAddress(string $address): array
    {
        $client = new Client(['verify' => false]);
        $addressEncode = $address;
        $response = $client->get('https://api.tomtom.com/search/2/geocode/%27.'.$addressEncode.'.%27.json', [
            'query' => [
                'key' => 'bZhPA555PRZ2tCDM2RaSbbHm4xg1LwVn',
                'limit' => 1
            ]
        ]);
        error_log(print_r($response,true));
        $data = json_decode($response->getBody(), true);
        $coordinates = $data['results'][0]['position'];
        return compact('coordinates');
    }
}