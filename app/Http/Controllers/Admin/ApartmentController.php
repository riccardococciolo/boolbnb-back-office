<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Models\Apartment;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Image;
use App\Models\Lead;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $apartments = Apartment::where('user_id', '=', Auth::user()->id)->get();
        return view('admin.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::all();
        return view('admin.apartments.create', compact ('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApartmentRequest $request)
    {
        $form_data = $request->validated();
        $apartment = new Apartment();
        $apartment->fill($form_data);

        $lat_lon = $this->getCoordinatesFromAddress($apartment->address);
        if($lat_lon['coordinates'] == 'errore'){
            return back()->withInput()->with('message', "L'indirizzo inserito non e' valito. Inserire indirizzo esistente.");
        } else {
            $apartment->longitude = $lat_lon['coordinates']['lon'];
            $apartment->latitude  = $lat_lon['coordinates']['lat'];

            $apartment->user_id   = Auth::id();

            $apartment->save();

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('public/apartment_images');
                    $image = new Image();
                    $image->image_path = $path;
                    $apartment->images()->save($image);
                }
            }

            if($request->has('services')) {
                $apartment->services()->attach($request->services);
            }
            
            return redirect()->route('admin.apartments.show', ['apartment' => $apartment->slug]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {
        
        if($apartment->user_id === Auth::user()->id) {
            $leads = Lead::where('apartment_id', $apartment->id)->get();
            return view('admin.apartments.show', compact('apartment', 'leads'));
        } else {
            return view('admin.apartments.error404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartment $apartment)
    {
        $images = $apartment->images;
        $services = Service::all();
        return view('admin.apartments.edit', compact('apartment', 'images', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApartmentRequest $request, Apartment $apartment)
    { {
            $form_data = $request->all();

            $imageToDelete = $request->input('image_to_delete', []);
            $lat_lon = $this->getCoordinatesFromAddress($apartment->address);
            if($lat_lon['coordinates'] == 'errore'){
                return back()->withInput()->with('message', "L'indirizzo inserito non e' valito. Inserire indirizzo esistente.");
            } else {
                $apartment->longitude = $lat_lon['coordinates']['lon'];
                $apartment->latitude  = $lat_lon['coordinates']['lat'];

                foreach ($imageToDelete as $imageId) {
                    
                    $image = Image::findOrFail($imageId);

                    Storage::delete($image->image_path);

                    $image->delete();
                }

                if ($request->hasFile('new_image')) {
                    foreach ($request->file('new_image') as $file) {
                        
                        $image_path = $file->store('public/apartment_images');

                        $apartment->images()->create([
                            'image_path' => $image_path
                        ]);
                    }
                }
            }
            $apartment->update($form_data);

            if($request->has('services')) {
                $apartment->services()->sync($request->services);
            } else {
                $apartment->services()->sync([]);
            }

            return redirect()->route('admin.apartments.show', ['apartment' => $apartment->slug])->with('message', 'l\'appartamento é stato modificato con successo');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {
        $this->checkUser($apartment);

        $apartment -> delete();
        // Storage::delete($apartment->image_path);

        return redirect()->route('admin.apartments.index')->with('message', 'Appartamento ' . $apartment->title . ' è stato cancellato');
    }

    public function checkUser(Apartment $apartment){
        if($apartment->user_id !== Auth::user()->id) {
            abort(404);
        }
    }


    public static function getCoordinatesFromAddress(string $address)
    {
        $client = new Client(['verify' => false]);
        $addressEncode = $address;
        $response = $client->get('https://api.tomtom.com/search/2/geocode/%27.' . $addressEncode . '.%27.json', [
            'query' => [
                'key' => 'bZhPA555PRZ2tCDM2RaSbbHm4xg1LwVn',
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
            // return response()->json(['error' => 'Indirizzo non trovato']);
            return $error;
        }
        // $coordinates = $data['results'][0]['position'];

        // return compact('coordinates');

    }
}
