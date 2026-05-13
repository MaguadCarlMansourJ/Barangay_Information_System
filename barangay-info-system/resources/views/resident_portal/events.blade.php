@extends('layouts.resident')

@section('content')
<h2 class="mb-4">Barangay Activities</h2>

<div class="row g-3">
    @forelse($events as $event)
        @php($registered = $event->eventParticipants->isNotEmpty())
        <div class="col-lg-6">
            <div class="portal-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <h5>{{ $event->title }}</h5>
                        <p class="text-muted mb-2">{{ $event->description }}</p>
                        <div class="small text-muted">
                            <i class="fas fa-calendar me-1"></i>{{ $event->event_date->format('M d, Y') }}
                            <i class="fas fa-location-dot ms-2 me-1"></i>{{ $event->location }}
                        </div>
                    </div>
                    <span class="badge text-bg-success">{{ $event->status }}</span>
                </div>
                <form method="POST" action="{{ route('resident-portal.events.register', $event) }}" class="mt-3">
                    @csrf
                    <button class="btn {{ $registered ? 'btn-secondary' : 'btn-brand' }}" {{ $registered ? 'disabled' : '' }}>
                        {{ $registered ? 'Registered' : 'Register' }}
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="portal-card p-4 text-center text-muted">No active barangay activities posted.</div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $events->links() }}</div>
@endsection
