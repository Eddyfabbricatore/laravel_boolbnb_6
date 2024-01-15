<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Functions\Helper;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
    {
        $form_data_apartment = $request->all();
        $form_data_apartment["slug"] = Helper::generateSlug($form_data_apartment["title"], Apartment::class);

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
        $title = 'Modifica Appartamento';
        $method = 'PUT';
        $route = route('admin.apartments.update', $apartment);
        $services = Service::all();
        return view("admin.apartments.create-edit", compact("apartment", "services", "title", "method", "route"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Apartment $apartment)
    {
        $form_data_apartment = $request->all();

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
        dd($form_data_apartment["address"]);
        if($form_data_apartment["title"] != $apartment->title){
            $form_data_apartment["slug"] = Helper::generateSlug($form_data_apartment["title"], Apartment::class);
        }else{
            $form_data_apartment["slug"] = $apartment->slug;
        }

        $apartment->update($form_data_apartment);

        if(array_key_exists("services", $form_data_apartment)){
            $apartment->services()->sync($form_data_apartment["services"]);
        }else{
            $apartment->services()->detach();
        }

        return redirect()->route("admin.apartments.show", $apartment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
