@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Event Details</h3>
    <div class="card">
        <div class="card-body">
            <h5>{{ $event->title }}</h5>
            <p><strong>Description:</strong> {{ $event->description }}</p>
            <p><strong>Location:</strong> {{ $event->location }}</p>
            <p><strong>Date:</strong> {{ $event->event_date->format('M d, Y') }}</p>
            <p><strong>Time:</strong> {{ $event->start_time }} - {{ $event->end_time }}</p>
            <p><strong>Status:</strong> {{ $event->status }}</p>
            <p><strong>Max Participants:</strong> {{ $event->max_participants ?? 'Unlimited' }}</p>
            <p><strong>Created By:</strong> {{ $event->createdBy->name ?? 'N/A' }}</p>

            <h6 class="mt-4">Participants</h6>
            <ul>
                @foreach($event->eventParticipants as $participant)
                <li>{{ $participant->resident->full_name ?? 'N/A' }} - {{ $participant->attendance_status }}</li>
                @endforeach
            </ul>
            <a href="{{ route('events.index') }}" class="btn btn-secondary">Back</a>
        </div>
</div>
@endsection
