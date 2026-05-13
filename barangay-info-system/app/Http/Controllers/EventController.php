<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Resident;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $viewType = $request->get('view', 'list');
        
        $query = Event::with('createdBy', 'eventParticipants');
        
        // Search by title or description
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }
        
        // Auto-update statuses
        $this->autoUpdateEventStatuses();
        
        $events = $query->orderBy('event_date', 'desc')->paginate(15)->withQueryString();
        
        $statuses = ['Upcoming', 'Ongoing', 'Completed', 'Cancelled'];
        $categories = Event::distinct('category')->pluck('category')->filter()->values();
        
        $calendarEvents = [];
        if ($viewType === 'calendar') {
            $calendarEvents = Event::select('id', 'title', 'event_date', 'start_time', 'status')
                ->get()
                ->map(function ($event) {
                    $start = $event->event_date->format('Y-m-d') . 'T' . $event->start_time;
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'start' => $start,
                        'url' => route('events.show', $event),
                        'backgroundColor' => match($event->status) {
                            'Upcoming' => '#7367f0',
                            'Ongoing' => '#ff9f43',
                            'Completed' => '#00d4aa',
                            'Cancelled' => '#ea5455',
                            default => '#6e6b7b',
                        },
                    ];
                })->toArray();
        }
        
        return view('events.index', compact('events', 'viewType', 'statuses', 'categories', 'calendarEvents'));
    }
    
    public function duplicate(Event $event)
    {
        $newEvent = $event->replicate();
        $newEvent->title .= ' (Copy)';
        $newEvent->status = 'Upcoming';
        $newEvent->save();
        
        return redirect()->route('events.edit', $newEvent)->with('success', 'Event duplicated successfully');
    }
    
    private function autoUpdateEventStatuses()
    {
        Event::where('status', 'Upcoming')
            ->where('event_date', '<', now()->format('Y-m-d'))
            ->update(['status' => 'Completed']);
            
        Event::where('status', 'Upcoming')
            ->whereDate('event_date', now()->format('Y-m-d'))
            ->where('start_time', '<=', now()->format('H:i:s'))
            ->update(['status' => 'Ongoing']);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_participants' => 'nullable|integer',
            'category' => 'required|string|max:100',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'Upcoming';

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully');
    }

    public function show(Event $event)
    {
        $event->load(['eventParticipants.resident', 'createdBy']);
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_participants' => 'nullable|integer',
            'category' => 'required|string|max:100',
            'status' => 'required|in:Upcoming,Ongoing,Completed,Cancelled',
        ]);

        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event updated successfully');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully');
    }

    public function register(Request $request, Event $event)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id|unique:event_participants,resident_id,NULL,id,event_id,' . $event->id,
        ]);

        EventParticipant::create([
            'event_id' => $event->id,
            'resident_id' => $request->resident_id,
            'attendance_status' => 'Registered',
            'registered_at' => now(),
        ]);

        return back()->with('success', 'Resident registered for event');
    }

    public function markAttendance(Request $request, Event $event, EventParticipant $participant)
    {
        $request->validate([
            'status' => 'required|in:Registered,Attended,Absent',
        ]);

        $participant->update(['attendance_status' => $request->status]);

        return back()->with('success', 'Attendance updated');
    }
}
