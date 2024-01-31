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
    public function index(Request $request, $slug){
        $apartment = Apartment::where('slug', $slug)->with('services', 'sponsors', 'user')->first();
        $selectedYear = Carbon::now()->format('Y');

        $viewStats = $apartment->views()
                                ->selectRaw('COUNT(*) as count, YEAR(view_date) as year, MONTH(view_date) as month')
                                ->whereYear('view_date', $selectedYear)
                                ->groupBy('year', 'month')
                                ->get();

        $messageStats = $apartment->messages()
                                ->selectRaw('COUNT(*) as count, YEAR(date) as year, MONTH(date) as month')
                                ->whereYear('date', $selectedYear)
                                ->groupBy('year', 'month')
                                ->get();

        // Assuming you have relationships defined in your Apartment and Sponsor models

        $sponsorAllYears = Sponsor::select('sponsors.name as sponsor_name', DB::raw('COUNT(*) as count'))
                    ->join('apartment_sponsor', 'sponsors.id', '=', 'apartment_sponsor.sponsor_id')
                    ->join('apartments', 'apartment_sponsor.apartment_id', '=', 'apartments.id')
                    ->where('apartments.id', $apartment->id)
                    ->groupBy('sponsors.name')
                    ->get();



        return view('admin.stats.index', compact('apartment', 'viewStats', 'messageStats', 'selectedYear','sponsorAllYears'));
    }

    public function updateViewChart(Request $request)
    {
        $selectedYear = $request->input('selectedYear', date('Y'));
        $apartmentId = $request->input('apartment');
        $apartment = Apartment::find($apartmentId);

        $viewStatsResponse = $apartment->views()
                                        ->selectRaw('COUNT(*) as count, YEAR(view_date) as year, MONTH(view_date) as month')
                                        ->whereYear('view_date', $selectedYear)
                                        ->groupBy('year', 'month')
                                        ->get();

        return response()->json(['response' => $viewStatsResponse]);
    }

    public function updateMessageChart(Request $request)
    {
        $selectedYear = $request->input('selectedYear', date('Y'));
        $apartmentId = $request->input('apartment');
        $apartment = Apartment::find($apartmentId);

        $messageStatsResponse = $apartment->messages()
                        ->selectRaw('COUNT(*) as count, YEAR(date) as year, MONTH(date) as month')
                        ->whereYear('date', $selectedYear)
                        ->groupBy('year', 'month')
                        ->get();

        return response()->json(['response' => $messageStatsResponse]);
    }



/*     public function updateSponsorChart(Request $request)
    {
        $selectedYear = $request->input('selectedYear', date('Y'));

        return response()->json(['messageStats' => $messageStats]);
    } */




}
