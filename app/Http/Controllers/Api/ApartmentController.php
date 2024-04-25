<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Service;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;


class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        $radius     = $request->input('kmRange', 20);
        $rooms      = $request->input('rooms_number');
        $beds       = $request->input('beds_number');
        $bathrooms  = $request->input('bathrooms_number');
        $apartments = Apartment::with('images', 'services');

        if($request->filled('address')){           
            $lat_lon = $this->getCoordinatesFromAddress($request->address);
            if($lat_lon['coordinates'] == 'errore'){
                return response()->json([
                    'success' => false,
                    'message' => 'Nessun appartamento trovato'
                ]);
            }
            else {
                $apartments = $this->scopeDistance($apartments, $lat_lon['coordinates']['lat'], $lat_lon['coordinates']['lon'], $radius);
            }        
        }

        $services_selected = $request->input('services', []);
        if($services_selected){
            $int_array = array_map('intval', $services_selected);

            foreach ($int_array as $service) {

                $apartments->whereHas('services', function ($query) use ($service) {
                    $query->where('id', $service);
                });
            }
        }
        
        if ($rooms > 0) {
            $apartments->where('rooms_number', '>=', $rooms);
        }
        if ($beds > 0) {
            $apartments->where('beds_number', '>=', $beds);
        }
        if ($bathrooms > 0) {
            $apartments->where('bathrooms_number', '>=', $bathrooms);
        }

        $finalQuery = $apartments->paginate(10);

        return response()->json([
            'results' => $finalQuery,
            'success' => true
        ]);
    }

    public function getSponsored(Request $request) 
    {
        $radius     = $request->input('kmRange', 20);
        $rooms      = $request->input('rooms_number');
        $beds       = $request->input('beds_number');
        $bathrooms  = $request->input('bathrooms_number');
        $apartments = Apartment::with('images', 'services', 'sponsors');
        $apartments->whereHas('sponsors', function ($query) {
            $query->where('expiration_date', '>', now());
        });

        if($request->filled('address')){           
            $lat_lon = $this->getCoordinatesFromAddress($request->address);
            if($lat_lon['coordinates'] == 'errore'){
                return response()->json([
                    'success' => false,
                    'message' => 'Nessun appartamento trovato'
                ]);
            }
            else {
                $apartments = $this->scopeDistance($apartments, $lat_lon['coordinates']['lat'], $lat_lon['coordinates']['lon'], $radius);
            }        
        }

        $services_selected = $request->input('services', []);
        if($services_selected){
            $int_array = array_map('intval', $services_selected);

            foreach ($int_array as $service) {

                $apartments->whereHas('services', function ($query) use ($service) {
                    $query->where('id', $service);
                });
            }
        }
        
        if ($rooms > 0) {
            $apartments->where('rooms_number', '>=', $rooms);
        }
        if ($beds > 0) {
            $apartments->where('beds_number', '>=', $beds);
        }
        if ($bathrooms > 0) {
            $apartments->where('bathrooms_number', '>=', $bathrooms);
        }

        $finalQuery = $apartments->get();

        return response()->json([
            'results' => $finalQuery,
            'success' => true
        ]);
    }

    public function show(string $slug)
    {

        $apartment = Apartment::with('images', 'services')->where('slug', $slug)->first();

        if($apartment) {
            return response()->json([
                'results' => $apartment,
                'success' => true 
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Nessun appartamento trovato'
            ]);
        }

    }

    public static function getCoordinatesFromAddress(string $address)
    {
        $client = new Client(['verify' => false]);
        $addressEncode = $address;
        $response = $client->get('https://api.tomtom.com/search/2/geocode/%27.' . $addressEncode . '.%27.json', [
            'query' => [
                // 'key' => 'bZhPA555PRZ2tCDM2RaSbbHm4xg1LwVn',
                'key' => '0Uo0D3xj0wcPYB8W6Ybk5SuoiIJK1I1M',
                'limit' => 1
            ]
        ]);
        error_log(print_r($response, true));
        $data = json_decode($response->getBody(), true);

        if (isset($data['results']) && count($data['results']) > 0) {
            $coordinates = $data['results'][0]['position'];
            return compact('coordinates');
        } else {
            $error = [
                'error'       => 'Indirizzo non trovato',
                'coordinates' => 'errore'
            ];
            return $error;
        }

    }

    public function scopeDistance($query, $from_latitude, $from_longitude, $distance = 20)
    {
        $between_coords = Apartment::calcCoordinates($from_longitude, $from_latitude, $distance);

        return $query
            ->where(function ($q) use ($between_coords) {
                $q->whereBetween('apartments.longitude', [$between_coords['min']['lng'], $between_coords['max']['lng']]);
            })
            ->where(function ($q) use ($between_coords) {
                $q->whereBetween('apartments.latitude', [$between_coords['min']['lat'], $between_coords['max']['lat']]);
            });
    }

    public function searchFilter(Request $request) 
    {

        $query = Apartment::query();

        // if ($request->filled('title')) {
        //     $query->where('title', 'like', '%' . $request->input('title') . '%');
        // }

        // if ($request->filled('address')) {
        //     $query->where('address', $request->input('address'));
        // }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        if ($request->filled('people')) {
            $query->where('beds_numbers', '>=', $request->input('people'));
        }

        if ($request->filled('rooms')) {
            $query->where('rooms_numbers', '>=', $request->input('rooms'));
        }

        $services_selected = $request->input('services', []);
        $int_array = array_map('intval', $services_selected);

        foreach ($int_array as $service) {

            $query->whereHas('services', function ($query) use ($service) {
                $query->where('name', $service);
            });
        }
        $appartamenti = $query->get();
        return view('risultati-ricerca', ['appartamenti' => $appartamenti]);

    }

    public function checkbox_filter(Request $request) {
        if($request->isMethod('post')){
            $data = $request->input();
            foreach($data as $key => $value) {
                $items = Apartment::join('apartment_service', 'apartments.id', '=', 'apartment_service.apartment_id')->join('services', 'services.id', '=', 'apartment_service.service_id')->whereIn('services.id', $data['services'])->get();
            }
            $result = json_encode($items, true);
            return $result;
            dd($result);
        }
    }
}
