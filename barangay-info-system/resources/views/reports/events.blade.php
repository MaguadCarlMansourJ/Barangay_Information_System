@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Events Report</h3>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Participants</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->event_date->format('M d, Y') }}</td>
                        <td>{{ $event->location }}</td>
                        <td>{{ $event->status }}</td>
                        <td>{{ $event->eventParticipants->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>
@endsection
