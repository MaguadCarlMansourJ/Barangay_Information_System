@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Blotter Cases</h3>
        <a href="{{ route('blotters.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Case</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Blotter #</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Incident Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blotters as $blotter)
                        <tr>
                            <td>{{ $blotter->blotter_number }}</td>
                            <td>{{ $blotter->type }}</td>
                            <td>
                                <span class="badge bg-{{ $blotter->status == 'Open' ? 'danger' : ($blotter->status == 'Under Investigation' ? 'warning' : ($blotter->status == 'Resolved' ? 'success' : 'secondary')) }}">
                                    {{ $blotter->status }}
                                </span>
                            </td>
                            <td>{{ $blotter->incident_date->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('blotters.show', $blotter) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('blotters.edit', $blotter) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('blotters.destroy', $blotter) }}" method="POST" class="d-inline">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $blotters->links() }}
        </div>
</div>
@endsection
