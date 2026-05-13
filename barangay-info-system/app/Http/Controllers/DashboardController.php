<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\DocumentRequest;
use App\Models\Event;
use App\Models\Blotter;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_residents' => Resident::count(),
            'total_males' => Resident::where('gender', 'Male')->count(),
            'total_females' => Resident::where('gender', 'Female')->count(),
            'pending_requests' => DocumentRequest::where('status', 'Pending')->count(),
            'upcoming_events' => Event::where('status', 'Upcoming')->count(),
            'active_blotters' => Blotter::whereNotIn('status', ['Resolved', 'Closed'])->count(),
            'total_revenue' => Payment::sum('amount'),
        ];

        $recentRequests = DocumentRequest::with(['resident', 'documentType'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentBlotters = Blotter::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $upcomingEvents = Event::where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(4)
            ->get();

        $populationByPurok = DB::table('residents')
            ->join('households', 'residents.household_id', '=', 'households.id')
            ->join('puroks', 'households.purok_id', '=', 'puroks.id')
            ->select('puroks.name', DB::raw('count(*) as total'))
            ->groupBy('puroks.name')
            ->get();

        return view('dashboard', compact('stats', 'recentRequests', 'recentBlotters', 'upcomingEvents', 'populationByPurok'));
    }
}

