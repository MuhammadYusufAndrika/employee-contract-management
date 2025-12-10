@extends('layouts.bootstrap')

@section('title', 'Contracts List')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-file-text"></i> Employee Contracts</h2>
                <a href="{{ route('contracts.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Contract
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            @if($contracts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>NIK</th>
                                <th>Job Position</th>
                                <th>Department</th>
                                <th>Work Location</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contracts as $contract)
                                <tr class="{{ $contract->isExpiringSoon() ? 'table-warning' : '' }}">
                                    <td>{{ $loop->iteration + ($contracts->currentPage() - 1) * $contracts->perPage() }}</td>
                                    <td>
                                        <strong>{{ $contract->employee_name }}</strong>
                                        @if($contract->isExpiringSoon())
                                            <span class="badge bg-warning text-dark ms-2">
                                                <i class="bi bi-exclamation-triangle"></i> Expiring Soon
                                            </span>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $contract->birthplace }}, {{ $contract->birthdate->format('d M Y') }}</small>
                                    </td>
                                    <td><small>{{ $contract->nik }}</small></td>
                                    <td>{{ $contract->job_position }}</td>
                                    <td>{{ $contract->department }}</td>
                                    <td>{{ $contract->work_location }}</td>
                                    <td>{{ $contract->start_date->format('d M Y') }}</td>
                                    <td>{{ $contract->end_date->format('d M Y') }}</td>
                                    <td>
                                        @if($contract->end_date < now())
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($contract->isExpiringSoon())
                                            <span class="badge bg-warning text-dark">
                                                {{ $contract->daysUntilExpiration() }} days left
                                            </span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($contract->isExpiringSoon() || $contract->end_date < now())
                                                <a href="{{ route('contracts.renew', $contract) }}" class="btn btn-sm btn-info" title="Renew Contract">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('contract-histories.show', $contract) }}" class="btn btn-sm btn-secondary" title="View History">
                                                <i class="bi bi-clock-history"></i>
                                            </a>
                                            <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this contract?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $contracts->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">No contracts found. Click "Add New Contract" to create one.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
