<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Message;
use App\Models\Sponsor;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Apartment $apartment, Request $request){
        $selectedYear = Carbon::now()->format('Y');

        $viewStats = View::selectRaw('COUNT(*) as count, YEAR(view_date) as year, MONTH(view_date) as month')
                        ->whereYear('view_date', $selectedYear)
                        ->groupBy('year', 'month')
                        ->get();

        $messageStats = Message::selectRaw('COUNT(*) as count, YEAR(date) as year, MONTH(date) as month')
                        ->whereYear('date', $selectedYear)
                        ->groupBy('year', 'month')
                        ->get();

        // Assuming you have relationships defined in your Apartment and Sponsor models

        $sponsorAllYears = Sponsor::select('sponsors.id as sponsor_id', DB::raw('COUNT(*) as count'))
                    ->join('apartment_sponsor', 'sponsors.id', '=', 'apartment_sponsor.sponsor_id')
                    ->join('apartments', 'apartment_sponsor.apartment_id', '=', 'apartments.id')
                    ->where('apartments.id', $apartment->id)
                    ->groupBy('sponsors.id')
                    ->get();



        return view('admin.stats.index', compact('apartment', 'viewStats', 'messageStats', 'selectedYear','sponsorAllYears'));
    }
    public function updateChart(Request $request)
    {
        $selectedYear = $request->input('selectedYear', date('Y'));

        $messageStats = Message::selectRaw('COUNT(*) as count, YEAR(date) as year, MONTH(date) as month')
                        ->whereYear('date', $selectedYear)
                        ->groupBy('year', 'month')
                        ->get();

        $viewStats = View::selectRaw('COUNT(*) as count, YEAR(view_date) as year, MONTH(view_date) as month')
                        ->whereYear('view_date', $selectedYear)
                        ->groupBy('year', 'month')
                        ->get();

        // Esegui le operazioni necessarie con il valore dell'anno selezionato
        // ...

        // Restituisci i dati aggiornati al frontend
        return response()->json(['messageStats' => $messageStats, 'viewStats' => $viewStats]);
    }




}
