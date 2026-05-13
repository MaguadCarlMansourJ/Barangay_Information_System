@extends('layouts.resident')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">My Document Requests</h2>
    <a href="{{ route('resident-portal.requests.create') }}" class="btn btn-brand">New Request</a>
</div>

<div class="portal-card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Request #</th><th>Document</th><th>Purpose</th><th>Status</th><th>Fee</th><th>Payment</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($requests as $request)
                    <tr>
                        <td>{{ $request->request_number }}</td>
                        <td>{{ $request->documentType->name ?? 'Document' }}</td>
                        <td>{{ $request->purpose }}</td>
                        <td><span class="badge text-bg-secondary">{{ $request->status }}</span></td>
                        <td>PHP {{ number_format($request->documentType->fee ?? 0, 2) }}</td>
                        <td>{{ $request->payment ? 'Paid' : (($request->documentType->fee ?? 0) > 0 ? 'For payment at barangay hall' : 'No fee') }}</td>
                        <td>{{ $request->date_requested->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No document requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $requests->links() }}
</div>
@endsection
