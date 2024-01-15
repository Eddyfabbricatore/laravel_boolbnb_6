<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $apartments = Apartment::all();
        return view('admin.dashboard', compact('apartments'));
    }
}
