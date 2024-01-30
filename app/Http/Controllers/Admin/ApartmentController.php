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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class ApartmentController extends Controller
{

    // front-end calls for all apartments
    public function getApartments() {

        $apartments = Apartment::with('services', 'sponsors')->get();
        $services = Service::all();

        foreach ($apartments as $apartment) {
            // Aggiungi l'attributo dinamico isSponsored
            $apartment->setAttribute('isSponsored', $this->isSponsored($apartment));
        }

        return response()->json(compact('apartments', 'services'));
    }

    protected function isSponsored($apartment)
{
    // Controlla se ci sono sponsorizzazioni associate
    $sponsorships = $apartment->sponsors;

    // Verifica se almeno una sponsorizzazione è attiva
    foreach ($sponsorships as $sponsorship) {
        if ($sponsorship->pivot->transaction_date !== null) {
            // Verifica se la sponsorizzazione è ancora attiva
            $sponsorEndTime = Carbon::parse($sponsorship->pivot->transaction_date)->addHours($sponsorship->duration_in_hours);

            // Se la sponsorizzazione è ancora attiva, restituisce true
            if (Carbon::now()->lt($sponsorEndTime)) {
                return $sponsorEndTime;
            }
        }
    }

    // Nessuna sponsorizzazione attiva o il tempo è scaduto
    return false;
}



public function getApartmentsTotal(Request $request) {
    // Recupera i valori precedenti dei parametri dalla query dell'URI
    $lonA = $request->input('lonA', 0);
    $latA = $request->input('latA', 0);
    $services = $request->input('services', []);
    $rooms = $request->input('rooms', 0);
    $beds = $request->input('beds', 0);
    $formRadius = $request->input('radius', 20000);

    // Salva i nuovi valori nella query dell'URI
    $request->merge([
        'lonA' => $lonA,
        'latA' => $latA,
        'services' => $services,
        'rooms' => $rooms,
        'beds' => $beds,
        'radius' => $formRadius,
    ]);

    // Continua con il tuo codice...
    // Ad esempio, la parte che segue la gestione dei parametri e la query

    $apartmentsQuery = Apartment::select(
        'apartments.*',
        DB::raw("ST_Distance_Sphere(point(?, ?), point(apartments.lng, apartments.lat)) as distance"),
        // DB::raw("CASE WHEN apartment_sponsor.end_sponsor_date >= NOW() THEN 1 ELSE 0 END as is_sponsored")
    )
    // ->join('apartment_sponsor', 'apartments.id', '=', 'apartment_sponsor.apartment_id')
    // ->join('sponsors', 'apartment_sponsor.sponsor_id', '=', 'sponsors.id')
    // ->orderByDesc('is_sponsored')
    ->orderBy('distance');

    // Condizioni per il raggio
    if ($formRadius > 0) {
        $apartmentsQuery->whereRaw("ST_Distance_Sphere(point(?, ?), point(apartments.lng, apartments.lat)) <= ?", [$lonA, $latA, $lonA, $latA, $formRadius]);
    }

    // Condizioni per le stanze e i letti
    $apartmentsQuery->where('rooms', '>=', $rooms)
        ->where('beds', '>=', $beds);

    // Condizioni per i servizi
    if (!empty($services)) {
        $apartmentsQuery->join('apartment_service as sa', 'apartments.id', '=', 'sa.apartment_id')
            ->join('services as s', 'sa.service_id', '=', 's.id')
            ->whereIn('s.name', $services)
            ->groupBy('apartments.id')
            ->havingRaw('COUNT(DISTINCT s.name) = ?', [count($services)])
            ->selectRaw('apartments.*, GROUP_CONCAT(s.name) AS service_names');
    }

    $apartments = $apartmentsQuery->get();

    foreach ($apartments as $apartment) {
        // Aggiungi l'attributo dinamico isSponsored
        $apartment->setAttribute('isSponsored', $this->isSponsored($apartment));
    }

    $response = response()->json(compact('apartments'));

    $response->header('Access-Control-Allow-Origin', '*');
    $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
    $response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');

    return $response;
}



    // public function getApartments() {
    //     try {
    //         $apartments = Apartment::with('services', 'sponsors')->get();
    //         $services = Service::all();
    //         $sponsoredApartments = Apartment::with('services', 'sponsors')->where('sponsor_id', '!=', null)->get();

    //         // Debugging
    //         dd(compact('apartments', 'services', 'sponsoredApartments'));

    //         return response()->json(compact('apartments', 'services', 'sponsoredApartments'));
    //     } catch (\Exception $e) {
    //         // Log dell'eccezione
    //         dd($e->getMessage());
    //     }
    // }




    // public function viewApartamentsInSearchAdvance(Request $request){

    //     $lonA = $request->input('lonA');
    //     $latA = $request->input('latA');

    //     // mi arriva una stringa con i due parametri, li divido usando ',' come separatore
    //     //$data = explode(',', $params);

    //     // assegno ai miei parametri, i valori dell'array 'data' che mi sono arrivati dalla chiamata API
    //     //$lonA = $data[0];
    //     //$latA = $data[1];


    //     $apartments = Apartment::with('services', 'sponsors')->get();

    //     $results = [];


    //     foreach ($apartments as $apartment) {
    //         $lonB = $apartment->lng;
    //         $latB = $apartment->lat;

    //         $expression = DB::raw('SELECT ST_Distance_Sphere(point(:lonA, :latA), point(:lonB, :latB)) as distance');

    //         // in laravel 10 la funzionalità Expression/Query/String è stata deprecata e per compatibilità aggiungiamo questa espressione
    //         $string = $expression->getValue(DB::connection()->getQueryGrammar());

    //         $distance = DB::select($string, [
    //             'lonA' => $lonA,
    //             'latA' => $latA,
    //             'lonB' => $lonB,
    //             'latB' => $latB,
    //         ]);

    //         // per comodità definisco in una variabile la distanza
    //         $realDistance = $distance[0]->distance;

    //         // questo elemento diventerà dinamico
    //         $radius = $formRadius;

    //         // condizione di validità - se la distanza è minore del raggio fornito allora pusho appartamento e distanza
    //         if ($realDistance < $radius) {
    //             $results[] = [
    //                 'appartamento' => $apartment,
    //                 'distanza' => $realDistance
    //             ];
    //         };

    //         usort($results, function ($a, $b) {
    //             return $a['distanza'] <=> $b['distanza'];
    //         });

    //     }
    //     return response()->json($results);
    // }


    public function getSingleApartment($slug) {
        $apartment = Apartment::where('slug', $slug)->with('services', 'sponsors', 'user')->get();
        return response()->json(compact('apartment'));
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
        $form_data_apartment['position_address'] = Helper::generateLatLng($form_data_apartment["address"]);

        $position = $form_data_apartment['position_address'][0];
        $lat = $position['lat'];
        $lon = $position['lon'];

        $form_data_apartment['lat'] = $lat;
        $form_data_apartment['lng'] = $lon;

        $address = $form_data_apartment['position_address'][1];
        $form_data_apartment['address'] = $address;

        $new_apartment = Apartment::create($form_data_apartment);

        $new_apartment->services()->attach($form_data_apartment["services"]);

        return redirect()->route("admin.apartments.show", $new_apartment->slug);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $apartment = Apartment::where('slug', $slug)->with('user', 'sponsors', 'services')->firstOrFail();


        /* AUTH CONTROL */
        if (auth()->user()->id != $apartment->user_id) {
            abort(404, 'Not Found');
        }
        return view("admin.apartments.show", compact("apartment"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $apartment = Apartment::where('slug', $slug)->with('user', 'sponsors', 'services')->firstOrFail();

        $apartmentSlug = $apartment->slug;
        /* AUTH CONTROL */
        if (auth()->user()->id != $apartment->user_id) {
            abort(404, 'Not Found');
        }

        $title = 'Modifica Appartamento';
        $method = 'PUT';
        $route = route('admin.apartments.update', $apartmentSlug);
        $services = Service::all();

        $form_data_address = Helper::getAddressDatas($apartment->lat, $apartment->lng);

        return view("admin.apartments.create-edit", compact("apartment", "services", "title", "method", "route", "form_data_address"));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ApartmentRequest $request, $slug)
    {
        $apartment = Apartment::where('slug', $slug)->with('user', 'sponsors', 'services')->firstOrFail();

        $form_data_apartment = $request->all();
        $form_data_apartment['user_id'] = Auth::user()->id;

        if(!array_key_exists("services", $form_data_apartment)){
            return redirect()->back()->with('updateServiceError', 'Per favore seleziona almeno un servizio');
        }

        /* GET FULL ADDRESS BY USER*/

        if($form_data_apartment['address'] != $apartment->address){
        /* GET LATITUTE E LONGITUDE */
            $form_data_apartment['position_address'] = Helper::generateLatLng($form_data_apartment["address"]);

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

        return redirect()->route("admin.apartments.show", $apartment->slug);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        $apartment = Apartment::where('slug', $slug)->with('user', 'sponsors', 'services')->firstOrFail();

        if($apartment->image){
            Storage::disk('public')->delete($apartment->image);
        }
        $apartment->delete();
        return redirect()->route('admin.apartments.index')->with('success',"L'appartamento è stato eliminato correttamente");
    }
}
