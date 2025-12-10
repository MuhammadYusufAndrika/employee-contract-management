@extends('layouts.bootstrap')

@section('title', 'Contract History')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-clock-history"></i> Contract History</h2>
            </div>
        </div>
    </div>

    <!-- Search by NIK -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-search"></i> Search Employee History by NIK</h5>
            <form action="{{ route('contract-histories.by-nik') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <input type="text" 
                           class="form-control" 
                           name="nik" 
                           placeholder="Enter employee NIK" 
                           required
                           maxlength="16">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search History
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- All History Records -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-list"></i> All Contract History Records</h5>
        </div>
        <div class="card-body">
            @if($histories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>NIK</th>
                                <th>Action</th>
                                <th>Contract Period</th>
                                <th>Job Position</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($histories as $history)
                                <tr>
                                    <td>{{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}</td>
                                    <td>
                                        {{ $history->created_at->format('d M Y') }}<br>
                                        <small class="text-muted">{{ $history->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $history->employee_name }}</strong><br>
                                        <small class="text-muted">{{ $history->department }}</small>
                                    </td>
                                    <td><small>{{ $history->nik }}</small></td>
                                    <td>
                                        @if($history->action_type == 'created')
                                            <span class="badge bg-success">Created</span>
                                        @elseif($history->action_type == 'renewed')
                                            <span class="badge bg-warning text-dark">Renewed</span>
                                        @else
                                            <span class="badge bg-info">Updated</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            {{ $history->start_date->format('d M Y') }} -<br>
                                            {{ $history->end_date->format('d M Y') }}
                                        </small>
                                    </td>
                                    <td>{{ $history->job_position }}</td>
                                    <td>
                                        @if($history->notes)
                                            <small>{{ Str::limit($history->notes, 30) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $histories->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">No history records found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
