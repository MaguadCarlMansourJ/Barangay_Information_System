@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Blotters Report</h3>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Blotter #</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Incident Date</th>
                        <th>Reported By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blotters as $blotter)
                    <tr>
                        <td>{{ $blotter->blotter_number }}</td>
                        <td>{{ $blotter->type }}</td>
                        <td>{{ $blotter->status }}</td>
                        <td>{{ $blotter->incident_date->format('M d, Y') }}</td>
                        <td>{{ $blotter->reportedBy->name ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>
@endsection
