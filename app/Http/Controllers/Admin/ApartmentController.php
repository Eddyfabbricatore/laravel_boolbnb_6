<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Functions\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ApartmentRequest;
use Illuminate\Support\Facades\DB;

class ApartmentController extends Controller
{

    // front-end calls for all apartments
    public function getApartments() {
        $apartments = Apartment::with('services', 'sponsors')->get();

        return response()->json(compact('apartments'));
    }

    public function viewApartamentsInSearchAdvance($params){

        // $lonA = $request->input('lonA');
        // $latA = $request->input('latA');

        // mi arriva una stringa con i due parametri, li divido usando ',' come separatore
        $data = explode(',', $params);

        // assegno ai miei parametri, i valori dell'array 'data' che mi sono arrivati dalla chiamata API
        $lonA = $data[0];
        $latA = $data[1];


        $apartments = Apartment::all();

        $results = [];


        foreach ($apartments as $apartment) {
            $lonB = $apartment->lng;
            $latB = $apartment->lat;

            $expression = DB::raw('SELECT ST_Distance_Sphere(point(:lonA, :latA), point(:lonB, :latB)) as distance');

            // in laravel 10 la funzionalità Expression/Query/String è stata deprecata e per compatibilità aggiungiamo questa espressione
            $string = $expression->getValue(DB::connection()->getQueryGrammar());

            $distance = DB::select($string, [
                'lonA' => $lonA,
                'latA' => $latA,
                'lonB' => $lonB,
                'latB' => $latB,
            ]);

            // per comodità definisco in una variabile la distanza
            $realDistance = $distance[0]->distance;

            // questo elemento diventerà dinamico
            $radius = 20001;

            // condizione di validità - se la distanza è minore del raggio fornito allora pusho appartamento e distanza
            if ($realDistance < $radius) {
                $results[] = [
                    'appartamento' => $apartment,
                    'distanza' => $realDistance
                ];
            };

            usort($results, function ($a, $b) {
                return $a['distanza'] <=> $b['distanza'];
            });

        }
        return response()->json($results);
    }





    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $apartments = $user->apartments;
        return view('admin.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Inserimento nuovo appartamento';
        $method = 'POST';
        $route = route('admin.apartments.store');
        $apartment = null;
        $services = Service::all();
        return view('admin.apartments.create-edit', compact("apartment", "services", "title", "method", "route"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApartmentRequest $request)
    {
        $form_data_apartment = $request->all();
        if(!array_key_exists("services", $form_data_apartment)){
            return redirect()->back()->with('createServiceError', 'Per favore seleziona almeno un servizio');
        }

        trim($form_data_apartment["title"]);

        $form_data_apartment["slug"] = Helper::generateSlug($form_data_apartment["title"], Apartment::class);

        if(array_key_exists('image',$form_data_apartment)){
            $form_data_apartment['image'] = Storage::put('uploads',$form_data_apartment['image']);
        } else {
            $form_data_apartment['image'] = 'no photo';
        }

        $form_data_apartment['user_id'] = Auth::user()->id;

        // chiamata api
        $form_data_apartment['position_address'] = Helper::generateLatLng($form_data_apartment["address"], 'lat','lon');

        $position = $form_data_apartment['position_address'][0];
        $lat = $position['lat'];
        $lon = $position['lon'];

        $form_data_apartment['lat'] = $lat;
        $form_data_apartment['lng'] = $lon;

        $address = $form_data_apartment['position_address'][1];
        $form_data_apartment['address'] = $address;

        $new_apartment = Apartment::create($form_data_apartment);

        $new_apartment->services()->attach($form_data_apartment["services"]);

        return redirect()->route("admin.apartments.show", $new_apartment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment)
    {
        /* AUTH CONTROL */
        if (auth()->user()->id != $apartment->user_id) {
            abort(404, 'Not Found');
        }
        return view("admin.apartments.show", compact("apartment"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
{
    /* AUTH CONTROL */
    if (auth()->user()->id != $apartment->user_id) {
        abort(404, 'Not Found');
    }

    $title = 'Modifica Appartamento';
    $method = 'PUT';
    $route = route('admin.apartments.update', $apartment);
    $services = Service::all();

    $form_data_address = Helper::getAddressDatas($apartment->lat, $apartment->lng);

    return view("admin.apartments.create-edit", compact("apartment", "services", "title", "method", "route", "form_data_address"));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(ApartmentRequest $request, Apartment $apartment)
    {
        $form_data_apartment = $request->all();
        $form_data_apartment['user_id'] = Auth::user()->id;

        if(!array_key_exists("services", $form_data_apartment)){
            return redirect()->back()->with('updateServiceError', 'Per favore seleziona almeno un servizio');
        }

        /* GET FULL ADDRESS BY USER*/

        if($form_data_apartment['address'] != $apartment->address){
        /* GET LATITUTE E LONGITUDE */
            $form_data_apartment['position_address'] = Helper::generateLatLng($form_data_apartment["address"], 'lat','lon');

            $position = $form_data_apartment['position_address'][0];
            $lat = $position['lat'];
            $lon = $position['lon'];

            $form_data_apartment['lat'] = $lat;
            $form_data_apartment['lng'] = $lon;

            $address = $form_data_apartment['position_address'][1];
            $form_data_apartment['address'] = $address;
        };


        /* SLUG */
        if($form_data_apartment["title"] != $apartment->title){
            $form_data_apartment["slug"] = Helper::generateSlug($form_data_apartment["title"], Apartment::class);
        }else{
            $form_data_apartment["slug"] = $apartment->slug;
        }

        /* IMAGE */
        if(array_key_exists('image',$form_data_apartment)){
            if($apartment->image){
                Storage::disk('public')->delete($apartment->image);
            }
            $form_data_apartment['image'] = Storage::put('uploads',$form_data_apartment['image']);
        }

        /* SERVICE */
        if(array_key_exists("services", $form_data_apartment)){
            $apartment->services()->sync($form_data_apartment["services"]);
        }else{
            $apartment->services()->detach();
        }

        $apartment->update($form_data_apartment);

        return redirect()->route("admin.apartments.show", $apartment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment)
    {
        if($apartment->image){
            Storage::disk('public')->delete($apartment->image);
        }
        $apartment->delete();
        return redirect()->route('admin.apartments.index')->with('success',"L'appartamento è stato eliminato correttamente");
    }
}
