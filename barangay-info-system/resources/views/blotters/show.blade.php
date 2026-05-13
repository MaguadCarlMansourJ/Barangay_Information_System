@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Blotter Case Details</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('blotters.edit', $blotter) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('blotters.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <p><strong>Blotter Number:</strong> {{ $blotter->blotter_number }}</p>
            <p><strong>Type:</strong> {{ $blotter->type }}</p>
            <p><strong>Description:</strong> {{ $blotter->description }}</p>
            <p><strong>Incident Date:</strong> {{ $blotter->incident_date->format('M d, Y') }} at {{ $blotter->incident_time }}</p>
            <p><strong>Location:</strong> {{ $blotter->incident_location }}</p>
            <p><strong>Status:</strong> {{ $blotter->status }}</p>
            <p><strong>Resolution:</strong> {{ $blotter->resolution ?? 'N/A' }}</p>
            <p><strong>Reported By:</strong> {{ $blotter->reportedBy->name ?? 'N/A' }}</p>

            @if($blotter->blotterParties->isNotEmpty())
                <hr>
                <h5>Parties Involved</h5>
                @foreach($blotter->blotterParties as $party)
                    <div class="border rounded p-3 mb-2">
                        <strong>{{ $party->party_type }}:</strong> {{ $party->resident->full_name ?? 'N/A' }}
                        <p class="mb-0 text-muted">{{ $party->statement ?? 'No statement recorded.' }}</p>
                    </div>
                @endforeach
            @endif

            @if($blotter->status != 'Resolved' && $blotter->status != 'Closed')
            <form action="{{ route('blotters.resolve', $blotter) }}" method="POST" class="mb-3">
                @csrf
                <div class="mb-2">
                    <label>Resolution</label>
                    <textarea name="resolution" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Resolve Case</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
