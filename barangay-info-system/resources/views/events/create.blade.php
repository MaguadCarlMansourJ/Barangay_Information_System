@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Add Event</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('events.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location') }}" required>
                </div>
                <div class="mb-3">
                    <label>Category</label>
                    <select name="category" class="form-control" required>
                        @foreach(['Health', 'Training', 'Community Service', 'Meeting', 'Sports', 'Cultural', 'Social Event', 'Disaster Preparedness'] as $category)
                            <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Event Date</label>
                        <input type="date" name="event_date" class="form-control" value="{{ old('event_date') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Start Time</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>End Time</label>
                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Max Participants</label>
                    <input type="number" name="max_participants" class="form-control" value="{{ old('max_participants') }}" min="1">
                </div>
                <button type="submit" class="btn btn-primary">Save Event</button>
                <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
