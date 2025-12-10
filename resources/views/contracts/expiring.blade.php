@extends('layouts.bootstrap')

@section('title', 'Expiring Contracts')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    <i class="bi bi-exclamation-triangle text-warning"></i> Contracts Expiring Soon
                    <span class="badge bg-danger ms-2">{{ $contracts->count() }}</span>
                </h2>
                <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    @if($contracts->count() > 0)
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-info-circle"></i> 
            <strong>Attention!</strong> The following contracts will expire within 30 days. Please take necessary action.
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-warning">
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Work Location</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days Remaining</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contracts as $contract)
                                <tr class="{{ $contract->daysUntilExpiration() <= 7 ? 'table-danger' : 'table-warning' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $contract->employee_name }}</strong>
                                        @if($contract->daysUntilExpiration() <= 7)
                                            <span class="badge bg-danger ms-2">Urgent</span>
                                        @endif
                                    </td>
                                    <td>{{ $contract->department }}</td>
                                    <td>{{ $contract->work_location }}</td>
                                    <td>{{ $contract->start_date->format('d M Y') }}</td>
                                    <td>{{ $contract->end_date ? $contract->end_date->format('d M Y') : 'Permanent' }}</td>
                                    <td>
                                        <span class="badge {{ $contract->daysUntilExpiration() <= 7 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                            <i class="bi bi-clock"></i> {{ $contract->daysUntilExpiration() }} days
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('contracts.renew', $contract) }}" class="btn btn-sm btn-primary" title="Renew Contract">
                                                <i class="bi bi-arrow-clockwise"></i> Renew
                                            </a>
                                            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-success">All Clear!</h4>
                <p class="text-muted">No contracts expiring within the next 30 days.</p>
                <a href="{{ route('contracts.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-file-text"></i> View All Contracts
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
