<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\DocumentType;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;

class ResidentPortalController extends Controller
{
    private function resident()
    {
        return auth()->user()->resident;
    }

    private function ensureResidentLinked()
    {
        if (! $this->resident()) {
            abort(403, 'Your portal account is not linked to a resident profile. Please contact the barangay secretary.');
        }
    }

    public function dashboard()
    {
        $this->ensureResidentLinked();

        $resident = $this->resident()->load(['household.purok']);
        $requests = DocumentRequest::with(['documentType', 'payment'])
            ->where('resident_id', $resident->id)
            ->latest()
            ->limit(5)
            ->get();
        $events = Event::whereIn('status', ['Upcoming', 'Ongoing'])
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->limit(4)
            ->get();

        $stats = [
            'requests' => DocumentRequest::where('resident_id', $resident->id)->count(),
            'pending' => DocumentRequest::where('resident_id', $resident->id)->where('status', 'Pending')->count(),
            'released' => DocumentRequest::where('resident_id', $resident->id)->where('status', 'Released')->count(),
            'events' => EventParticipant::where('resident_id', $resident->id)->count(),
        ];

        return view('resident_portal.dashboard', compact('resident', 'requests', 'events', 'stats'));
    }

    public function profile()
    {
        $this->ensureResidentLinked();

        $resident = $this->resident()->load(['household.purok']);

        return view('resident_portal.profile', compact('resident'));
    }

    public function requests()
    {
        $this->ensureResidentLinked();

        $resident = $this->resident();
        $requests = DocumentRequest::with(['documentType', 'payment'])
            ->where('resident_id', $resident->id)
            ->latest()
            ->paginate(10);

        return view('resident_portal.requests', compact('requests'));
    }

    public function createRequest()
    {
        $this->ensureResidentLinked();

        $documentTypes = DocumentType::orderBy('name')->get();

        return view('resident_portal.request-create', compact('documentTypes'));
    }

    public function storeRequest(Request $request)
    {
        $this->ensureResidentLinked();

        $validated = $request->validate([
            'document_type_id' => 'required|exists:document_types,id',
            'purpose' => 'required|string|max:1000',
        ]);

        DocumentRequest::create([
            'resident_id' => $this->resident()->id,
            'document_type_id' => $validated['document_type_id'],
            'request_number' => 'DOC-' . now()->format('ymd') . '-' . strtoupper(substr(uniqid(), -6)),
            'status' => 'Pending',
            'purpose' => $validated['purpose'],
            'date_requested' => now()->toDateString(),
        ]);

        return redirect()->route('resident-portal.requests')->with('success', 'Document request submitted successfully.');
    }

    public function events()
    {
        $this->ensureResidentLinked();

        $resident = $this->resident();
        $events = Event::with(['eventParticipants' => function ($query) use ($resident) {
                $query->where('resident_id', $resident->id);
            }])
            ->whereIn('status', ['Upcoming', 'Ongoing'])
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->paginate(10);

        return view('resident_portal.events', compact('events'));
    }

    public function registerEvent(Event $event)
    {
        $this->ensureResidentLinked();

        $resident = $this->resident();

        if ($event->max_participants && $event->eventParticipants()->count() >= $event->max_participants) {
            return back()->with('error', 'This event has already reached its participant limit.');
        }

        EventParticipant::firstOrCreate(
            ['event_id' => $event->id, 'resident_id' => $resident->id],
            ['attendance_status' => 'Registered', 'registered_at' => now()]
        );

        return back()->with('success', 'You are registered for this barangay activity.');
    }
}
