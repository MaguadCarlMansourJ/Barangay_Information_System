@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @php($isEdit = isset($document))

    <h3 class="mb-4">{{ $isEdit ? 'Edit Document Request' : 'New Document Request' }}</h3>

    <div class="card">
        <div class="card-body">
            <form action="{{ $isEdit ? route('documents.update', $document) : route('documents.store') }}" method="POST">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="form-label">Resident</label>
                    <select name="resident_id" class="form-control" required>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}" {{ old('resident_id', $document->resident_id ?? '') == $resident->id ? 'selected' : '' }}>
                                {{ $resident->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Document Type</label>
                    <select name="document_type_id" class="form-control" required>
                        @foreach($documentTypes as $type)
                            <option value="{{ $type->id }}" {{ old('document_type_id', $document->document_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} (PHP {{ number_format($type->fee, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Purpose</label>
                    <textarea name="purpose" class="form-control" required>{{ old('purpose', $document->purpose ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date Requested</label>
                    <input type="date" name="date_requested" class="form-control" value="{{ old('date_requested', $isEdit ? $document->date_requested->format('Y-m-d') : date('Y-m-d')) }}" required>
                </div>

                @if($isEdit)
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            @foreach(['Pending', 'Approved', 'Processing', 'Ready', 'Released', 'Rejected'] as $status)
                                <option value="{{ $status }}" {{ old('status', $document->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control">{{ old('remarks', $document->remarks) }}</textarea>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Request' : 'Submit Request' }}</button>
                <a href="{{ route('documents.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
