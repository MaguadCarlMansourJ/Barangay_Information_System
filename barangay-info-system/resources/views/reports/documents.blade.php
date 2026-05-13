@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Documents Report</h3>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Request #</th>
                        <th>Resident</th>
                        <th>Document Type</th>
                        <th>Status</th>
                        <th>Date Requested</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr>
                        <td>{{ $request->request_number }}</td>
                        <td>{{ $request->resident->full_name ?? 'N/A' }}</td>
                        <td>{{ $request->documentType->name }}</td>
                        <td>{{ $request->status }}</td>
                        <td>{{ $request->date_requested->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>
@endsection
