@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">New Blotter Case</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('blotters.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Type</label>
                    <select name="type" class="form-control" required>
                        <option value="Complaint">Complaint</option>
                        <option value="Incident">Incident</option>
                        <option value="Dispute">Dispute</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Complainant</label>
                        <select name="complainant_id" class="form-control">
                            <option value="">Walk-in / Not encoded resident</option>
                            @foreach($residents as $resident)
                                <option value="{{ $resident->id }}">{{ $resident->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Respondent</label>
                        <select name="respondent_id" class="form-control">
                            <option value="">Unknown / Not encoded resident</option>
                            @foreach($residents as $resident)
                                <option value="{{ $resident->id }}">{{ $resident->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Incident Date</label>
                        <input type="date" name="incident_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Incident Time</label>
                        <input type="time" name="incident_time" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Location</label>
                        <input type="text" name="incident_location" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Complainant Statement</label>
                        <textarea name="complainant_statement" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Respondent Statement</label>
                        <textarea name="respondent_statement" class="form-control"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Case</button>
                <a href="{{ route('blotters.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
