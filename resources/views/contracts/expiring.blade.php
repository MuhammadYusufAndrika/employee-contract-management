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

    <!-- Period Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('contracts.expiring') }}" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label for="period" class="form-label fw-semibold">
                        <i class="bi bi-calendar-range text-primary me-1"></i>Filter by Expiration Period
                    </label>
                    <select class="form-select" id="period" name="period" onchange="this.form.submit()">
                        <option value="1" {{ $period == '1' ? 'selected' : '' }}>Within 1 Month (30 days)</option>
                        <option value="3" {{ $period == '3' ? 'selected' : '' }}>Within 3 Months (90 days)</option>
                        <option value="6" {{ $period == '6' ? 'selected' : '' }}>Within 6 Months (180 days)</option>
                        <option value="12" {{ $period == '12' ? 'selected' : '' }}>Within 1 Year (365 days)</option>
                        <option value="12+" {{ $period == '12+' ? 'selected' : '' }}>More than 1 Year</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Apply Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($contracts->count() > 0)
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-info-circle"></i> 
            <strong>Attention!</strong> Found {{ $contracts->count() }} contract(s) 
            @if($period == '1')
                expiring within 30 days.
            @elseif($period == '3')
                expiring within 3 months.
            @elseif($period == '6')
                expiring within 6 months.
            @elseif($period == '12')
                expiring within 1 year.
            @else
                expiring more than 1 year from now.
            @endif
            Please take necessary action.
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-warning">
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>NIK</th>
                                <th>Department</th>
                                <th>Point of Hire</th>
                                <th>Work Location</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days Remaining</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contracts as $contract)
                                @php
                                    $daysRemaining = $contract->daysUntilExpiration();
                                    $urgency = $daysRemaining <= 7 ? 'danger' : ($daysRemaining <= 30 ? 'warning' : 'info');
                                @endphp
                                <tr class="{{ $urgency === 'danger' ? 'table-danger' : ($urgency === 'warning' ? 'table-warning' : '') }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $contract->employee->employee_name ?? 'N/A' }}</strong>
                                        @if($daysRemaining <= 7)
                                            <span class="badge bg-danger ms-2">Urgent</span>
                                        @elseif($daysRemaining <= 30)
                                            <span class="badge bg-warning text-dark ms-2">Soon</span>
                                        @endif
                                    </td>
                                    <td>{{ $contract->employee->nik ?? 'N/A' }}</td>
                                    <td>{{ $contract->department }}</td>
                                    <td>{{ $contract->point_of_hire }}</td>
                                    <td>{{ $contract->work_location }}</td>
                                    <td>{{ $contract->start_date->format('d M Y') }}</td>
                                    <td>{{ $contract->end_date ? $contract->end_date->format('d M Y') : 'Permanent' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $urgency }} {{ $urgency === 'warning' ? 'text-dark' : '' }}">
                                            <i class="bi bi-clock"></i> {{ $daysRemaining }} days
                                        </span>
                                    </td>
                                    <td>
                                        @if(auth()->user()->isAdmin())
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('contracts.renew', $contract) }}" 
                                                   class="btn btn-sm btn-success" 
                                                   title="Renew Contract">
                                                    <i class="bi bi-arrow-clockwise"></i> Renew
                                                </a>
                                                <a href="{{ route('contracts.show', $contract) }}" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('contracts.edit', $contract) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        @else
                                            <a href="{{ route('contracts.show', $contract) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        @endif
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
                <p class="text-muted">
                    No contracts expiring 
                    @if($period == '1')
                        within the next 30 days.
                    @elseif($period == '3')
                        within the next 3 months.
                    @elseif($period == '6')
                        within the next 6 months.
                    @elseif($period == '12')
                        within the next year.
                    @else
                        more than 1 year from now.
                    @endif
                </p>
                <a href="{{ route('contracts.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-file-text"></i> View All Contracts
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.table-hover tbody tr:hover {
    cursor: pointer;
}

.btn-group .btn {
    white-space: nowrap;
}
</style>
@endsection
