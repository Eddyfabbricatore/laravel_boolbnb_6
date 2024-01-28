<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\View;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(Apartment $apartment){
        $statsView = View::selectRaw('COUNT(*) as count, DATE(view_date) as date')
                        ->groupBy('date')
                        ->get();

        return view('admin.stats.index', compact('apartment', 'statsView'));
    }
}
