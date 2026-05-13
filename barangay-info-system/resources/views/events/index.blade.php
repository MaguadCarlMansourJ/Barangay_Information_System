@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Events Management</h3>
        <p class="text-muted mb-0">{{ $events->total() }} events found</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('events.index', array_merge(request()->all(), ['view' => 'calendar'])) }}" class="btn {{ $viewType == 'calendar' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-calendar-alt"></i> Calendar
        </a>
        <a href="{{ route('events.index', array_merge(request()->all(), ['view' => 'list'])) }}" class="btn {{ $viewType == 'list' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-list"></i> List
        </a>
        <a href="{{ route('events.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> New Event
        </a>
    </div>
</div>

{{-- Filters Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pb-0">
        <h6 class="card-title mb-0"><i class="fas fa-filter me-2 text-muted"></i>Filters & Search</h6>
    </div>
    <div class="card-body pt-0">
        <form action="{{ route('events.index') }}" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="view" value="{{ $viewType }}">
            <div class="col-lg-3 col-md-6">
                <label class="form-label">Search Events</label>
                <input type="text" name="search" class="form-control" placeholder="Title or description..." value="{{ request('search') }}">
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-lg-1 col-md-3">
                <button type="submit" class="btn btn-primary w-100 h-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        @if(request()->hasAny(['search','status','category','date_from','date_to']))
        <div class="mt-3">
            <a href="{{ route('events.index', ['view' => $viewType]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i> Clear All Filters
            </a>
        </div>
        @endif
    </div>
</div>

@if($viewType == 'calendar')
    {{-- Calendar View --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div id="eventCalendar" style="height: 70vh;"></div>
        </div>
    </div>
@else
    {{-- Professional Card Layout --}}
    <div class="row g-4">
        @forelse($events as $event)
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-2 flex-grow-1">{{ Str::limit($event->title, 50) }}</h5>
                        <span class="badge bg-{{ 
                            $event->status == 'Upcoming' ? 'primary' : 
                            ($event->status == 'Ongoing' ? 'warning text-dark' : 
                            ($event->status == 'Completed' ? 'success' : 'secondary'))
                        }} fs-6 fw-semibold px-3 py-2">{{ $event->status }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="text-muted d-block mb-1">
                            <i class="fas fa-tag me-1"></i>{{ $event->category ?? 'General Event' }}
                        </span>
                        <span class="text-muted d-block mb-1">
                            <i class="fas fa-calendar me-1"></i>{{ $event->event_date->format('M d, Y') }}
                        </span>
                        <span class="text-muted">
                            <i class="fas fa-clock me-1"></i>{{ $event->start_time }} - {{ $event->end_time }}
                        </span>
                    </div>
                    
                    <p class="text-muted small mb-3">{{ Str::limit($event->description, 100) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-users me-1"></i>
                            @if($event->max_participants)
                                {{ $event->eventParticipants->count() }} / {{ $event->max_participants }}
                            @else
                                {{ $event->eventParticipants->count() }}
                            @endif
                        </span>
                        <span class="text-muted small">{{ $event->location }}</span>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('events.duplicate', $event) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm" title="Duplicate">
                                <i class="fas fa-copy"></i>
                            </button>
                        </form>
                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this event?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No events found</h4>
                <p class="text-muted">Create your first event to get started.</p>
                <a href="{{ route('events.create') }}" class="btn btn-primary">Create Event</a>
            </div>
        </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $events->appends(request()->query())->links() }}
    </div>
@endif
@endsection

@push('scripts')
<style>
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}
</style>

@if($viewType == 'calendar')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('eventCalendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        events: @json($calendarEvents),
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },
        eventDidMount: function(info) {
            info.el.style.cursor = 'pointer';
        }
    });
    calendar.render();
});
</script>
@endif
@endpush

