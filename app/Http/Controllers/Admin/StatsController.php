<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Message;
use App\Models\Sponsor;
use App\Models\View;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(Apartment $apartment, Request $request){
        $selectedYear = '';

        $viewStats = View::selectRaw('COUNT(*) as count, DATE(view_date) as date')
                        ->groupBy('date')
                        ->get();

        $messageStats = Message::selectRaw('COUNT(*) as count, YEAR(date) as year, MONTH(date) as month')
                        ->whereYear('date', $selectedYear)
                        ->groupBy('year', 'month')
                        ->get();


        return view('admin.stats.index', compact('apartment', 'viewStats', 'messageStats', 'selectedYear'));
    }
        public function updateChart(Request $request)
        {
            $selectedYear = $request->input('selectedYear', date('Y'));

            $messageStats = Message::selectRaw('COUNT(*) as count, YEAR(date) as year, MONTH(date) as month')
                            ->whereYear('date', $selectedYear)
                            ->groupBy('year', 'month')
                            ->get();

            // Esegui le operazioni necessarie con il valore dell'anno selezionato
            // ...

            // Restituisci i dati aggiornati al frontend
            return response()->json(['messageStats' => $messageStats]);
        }



}
