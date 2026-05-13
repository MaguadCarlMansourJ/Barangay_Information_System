@extends('layouts.resident')

@section('content')
<h2 class="mb-4">Request Barangay Document</h2>

<div class="portal-card p-4">
    <form method="POST" action="{{ route('resident-portal.requests.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Document Type</label>
            <select name="document_type_id" class="form-select" required>
                @foreach($documentTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }} | PHP {{ number_format($type->fee, 2) }} | {{ $type->processing_days }} day{{ $type->processing_days == 1 ? '' : 's' }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Purpose</label>
            <textarea name="purpose" class="form-control" rows="4" required placeholder="Example: Employment requirement, scholarship application, medical assistance"></textarea>
        </div>
        <button class="btn btn-brand" type="submit">Submit Request</button>
        <a href="{{ route('resident-portal.requests') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
