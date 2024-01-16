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


class ApartmentController extends Controller
{
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
        trim($form_data_apartment["title"]);
        $form_data_apartment["slug"] = Helper::generateSlug($form_data_apartment["title"], Apartment::class);

        if(array_key_exists('image',$form_data_apartment)){
            $form_data_apartment['image'] = Storage::put('uploads',$form_data_apartment['image']);
        } else {
            $form_data_apartment['image'] = 'no photo';
        }

        $form_data_apartment['user_id'] = Auth::user()->id;

        $form_data_apartment["address"] =
        Helper::generateFullAddress(
            $form_data_apartment["street_address"],
            $form_data_apartment["street_number"],
            $form_data_apartment["cap"],
            $form_data_apartment["city"],
            $form_data_apartment["province"],
            $form_data_apartment["region"],
            $form_data_apartment["country"]
        );

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

        if(array_key_exists("services", $form_data_apartment)){
            $new_apartment -> services()->attach($form_data_apartment["services"]);
        }

        return redirect()->route("admin.apartments.show", $new_apartment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Apartment $apartment)
    {
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

        /* GET FULL ADDRESS BY USER*/
        $form_data_apartment["address"] =
        Helper::generateFullAddress(
            $form_data_apartment["street_address"],
            $form_data_apartment["street_number"],
            $form_data_apartment["cap"],
            $form_data_apartment["city"],
            $form_data_apartment["province"],
            $form_data_apartment["region"],
            $form_data_apartment["country"]
        );

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
