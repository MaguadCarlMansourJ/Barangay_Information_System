@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Household</h2>
            <p class="text-muted mb-0">{{ $household->house_number }} - {{ $household->address }}</p>
        </div>
        <a href="{{ route('households.show', $household) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please check the form.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('households.update', $household) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Purok</label>
                        <select name="purok_id" class="form-select" required>
                            @foreach($puroks as $purok)
                                <option value="{{ $purok->id }}" {{ old('purok_id', $household->purok_id) == $purok->id ? 'selected' : '' }}>{{ $purok->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">House Number</label>
                        <input type="text" name="house_number" class="form-control" value="{{ old('house_number', $household->house_number) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $household->address) }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('households.show', $household) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Household
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
